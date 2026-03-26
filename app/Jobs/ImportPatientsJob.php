<?php

namespace App\Jobs;

use App\Models\BloodType;
use App\Models\ImportLog;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportPatientsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 600; // 10 minutos

    public function __construct(
        public readonly string $filePath,
        public readonly int $importLogId
    ) {}

    public function handle(): void
    {
        $importLog = ImportLog::findOrFail($this->importLogId);
        $importLog->update(['status' => 'processing']);

        $errors = [];
        $processed = 0;
        $failed = 0;

        try {
            $fullPath = Storage::path($this->filePath);

            if (! file_exists($fullPath)) {
                $importLog->update(['status' => 'failed', 'errors' => ['El archivo no fue encontrado.']]);
                return;
            }

            $handle = fopen($fullPath, 'r');
            $header = fgetcsv($handle); // primera fila = encabezados

            if ($header === false) {
                $importLog->update(['status' => 'failed', 'errors' => ['El archivo está vacío o no es un CSV válido.']]);
                fclose($handle);
                return;
            }

            // Normalizar encabezados (trim + minúsculas)
            $header = array_map(fn($h) => trim(strtolower($h)), $header);

            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                if (count($row) !== count($header)) {
                    $errors[] = "Fila {$rowNumber}: número de columnas incorrecto.";
                    $failed++;
                    continue;
                }

                $data = array_combine($header, $row);

                try {
                    $this->processRow($data, $rowNumber);
                    $processed++;
                } catch (\Throwable $e) {
                    $errors[] = "Fila {$rowNumber}: " . $e->getMessage();
                    $failed++;
                    Log::warning("ImportPatientsJob - Fila {$rowNumber} fallida: " . $e->getMessage());
                }

                // Actualizar progreso cada 50 filas
                if ($rowNumber % 50 === 0) {
                    $importLog->update([
                        'processed_rows' => $processed,
                        'failed_rows'    => $failed,
                    ]);
                }
            }

            fclose($handle);
            Storage::delete($this->filePath);

            $importLog->update([
                'status'         => 'completed',
                'processed_rows' => $processed,
                'failed_rows'    => $failed,
                'errors'         => $errors ?: null,
            ]);

        } catch (\Throwable $e) {
            Log::error('ImportPatientsJob falló: ' . $e->getMessage());
            $importLog->update([
                'status'         => 'failed',
                'processed_rows' => $processed,
                'failed_rows'    => $failed,
                'errors'         => array_merge($errors, ['Error crítico: ' . $e->getMessage()]),
            ]);
        }
    }

    private function processRow(array $data, int $rowNumber): void
    {
        $name    = trim($data['nombre'] ?? $data['name'] ?? '');
        $email   = trim($data['email'] ?? $data['correo'] ?? '');
        $idNum   = trim($data['cedula'] ?? $data['id_number'] ?? $data['numero_identidad'] ?? '');
        $phone   = trim($data['telefono'] ?? $data['phone'] ?? '');
        $address = trim($data['direccion'] ?? $data['address'] ?? '');

        if (empty($name) || empty($email) || empty($idNum)) {
            throw new \InvalidArgumentException('Los campos nombre, email y cédula son obligatorios.');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("El email '{$email}' no es válido.");
        }

        if (User::where('email', $email)->exists() || User::where('id_number', $idNum)->exists()) {
            throw new \InvalidArgumentException("El usuario con email '{$email}' o cédula '{$idNum}' ya existe.");
        }

        // Resolver tipo de sangre por nombre (opcional)
        $bloodTypeId = null;
        $bloodTypeName = trim($data['tipo_sangre'] ?? $data['blood_type'] ?? '');
        if ($bloodTypeName !== '') {
            $bloodType = BloodType::where('name', $bloodTypeName)->first();
            if ($bloodType) {
                $bloodTypeId = $bloodType->id;
            }
        }

        DB::transaction(function () use ($data, $name, $email, $idNum, $phone, $address, $bloodTypeId) {
            $user = User::create([
                'name'      => $name,
                'email'     => $email,
                'id_number' => $idNum,
                'phone'     => $phone ?: 'N/A',
                'address'   => $address ?: 'N/A',
                'password'  => Hash::make(Str::random(16)),
            ]);

            Patient::create([
                'user_id'                       => $user->id,
                'blood_type_id'                 => $bloodTypeId,
                'allergies'                     => $data['alergias'] ?? $data['allergies'] ?? null,
                'chronic_conditions'            => $data['enfermedades_cronicas'] ?? $data['chronic_conditions'] ?? null,
                'surgical_history'              => $data['historial_quirurgico'] ?? $data['surgical_history'] ?? null,
                'family_history'                => $data['historial_familiar'] ?? $data['family_history'] ?? null,
                'emergency_contact_name'        => $data['contacto_emergencia'] ?? $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone'       => $data['telefono_emergencia'] ?? $data['emergency_contact_phone'] ?? null,
                'emergency_contact_relationship'=> $data['relacion_emergencia'] ?? $data['emergency_contact_relationship'] ?? null,
            ]);
        });
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ImportPatientsJob falló definitivamente: ' . $exception->getMessage());

        ImportLog::where('id', $this->importLogId)->update([
            'status' => 'failed',
            'errors' => ['El job fue rechazado por la cola: ' . $exception->getMessage()],
        ]);
    }
}

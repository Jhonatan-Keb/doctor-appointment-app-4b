<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\ImportPatientsJob;
use App\Models\ImportLog;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();
        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'allergies' => 'nullable|string|min:3|max:250',
            'chronic_conditions' => 'nullable|string|min:3|max:255',
            'surgical_history' => 'nullable|string|min:3|max:255',
            'family_history' => 'nullable|string|min:3|max:255',
            'observations' => 'nullable|string|min:3|max:250',
            'emergency_contact_name' => 'nullable|string|min:3|max:255',
            'emergency_contact_phone' => ['nullable', 'string', 'min:10', 'max:12'],
            'emergency_contact_relationship' => 'nullable|string|min:3|max:50',
        ]);

        $patient->update($data);

        session()->flash(
            'swal',
            [
                'icon' => 'success',
                'title' => '¡Paciente actualizado!',
                'text' => 'Los datos del paciente se han actualizado correctamente.',
            ]
        );
        return redirect()->route('admin.admin.patients.edit', $patient)->with('success', 'Paciente actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }

    /**
     * Muestra el formulario para importación masiva de pacientes.
     */
    public function importForm()
    {
        $imports = ImportLog::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return view('admin.patients.import', compact('imports'));
    }

    /**
     * Recibe el archivo CSV/Excel y despacha el job en segundo plano.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:51200', // max 50 MB
        ], [
            'file.required' => 'Debes seleccionar un archivo.',
            'file.mimes'    => 'Solo se aceptan archivos CSV (.csv).',
            'file.max'      => 'El archivo no debe superar los 50 MB.',
        ]);

        $uploadedFile = $request->file('file');
        $fileName     = $uploadedFile->getClientOriginalName();

        // Guardar en storage/app/imports de forma temporal
        $path = $uploadedFile->store('imports');

        // Contar filas (excluyendo encabezado)
        $totalRows = max(0, count(file(Storage::path($path))) - 1);

        // Crear registro de log
        $importLog = ImportLog::create([
            'user_id'   => Auth::id(),
            'file_name' => $fileName,
            'status'    => 'pending',
            'total_rows'=> $totalRows,
        ]);

        // Despachar el job a la cola (segundo plano)
        ImportPatientsJob::dispatch($path, $importLog->id);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Importación iniciada!',
            'text'  => "El archivo '{$fileName}' está siendo procesado en segundo plano. Puedes consultar el estado más abajo.",
        ]);

        return redirect()->route('admin.admin.patients.import-form');
    }

    /**
     * Descarga la plantilla CSV de ejemplo.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_pacientes.csv"',
        ];

        $columns = [
            'nombre', 'email', 'cedula', 'telefono', 'direccion',
            'tipo_sangre', 'alergias', 'enfermedades_cronicas',
            'historial_quirurgico', 'historial_familiar',
            'contacto_emergencia', 'telefono_emergencia', 'relacion_emergencia',
        ];

        $example = [
            'Juan Pérez', 'juan.perez@ejemplo.com', '1234567890',
            '8888-8888', 'Calle Principal 123',
            'O+', 'Polen', 'Diabetes tipo 2',
            'Apendicectomía 2010', 'Hipertensión paterna',
            'María Pérez', '7777-7777', 'Esposa',
        ];

        $callback = function () use ($columns, $example) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($handle, $columns);
            fputcsv($handle, $example);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

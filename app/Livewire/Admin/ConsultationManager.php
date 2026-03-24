<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Consultation;
use Livewire\Component;

class ConsultationManager extends Component
{
    public $appointmentId;
    public $appointment;

    // Consultation fields
    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';

    // Medications (prescription) - matches blade variable name
    public $medicines = [];

    // UI state
    public $activeTab = 'consulta';
    public $showPreviousModal = false;

    // Previous consultations data
    public $previousConsultations = [];

    // Patient history for modal (stored as array to survive Livewire dehydration)
    public $patientHistory = [];

    protected $rules = [
        'diagnosis' => 'required|string|min:3',
        'treatment' => 'required|string|min:3',
        'notes' => 'nullable|string',
        'medicines.*.name' => 'nullable|string',
        'medicines.*.dose' => 'nullable|string',
        'medicines.*.frequency' => 'nullable|string',
        'medicines.*.duration' => 'nullable|string',
    ];

    protected $messages = [
        'diagnosis.required' => 'El diagnóstico es obligatorio.',
        'diagnosis.min' => 'El diagnóstico debe tener al menos 3 caracteres.',
        'treatment.required' => 'El tratamiento es obligatorio.',
        'treatment.min' => 'El tratamiento debe tener al menos 3 caracteres.',
    ];

    public function mount($appointmentId)
    {
        $this->appointmentId = $appointmentId;
        $this->appointment = Appointment::with([
            'patient.user',
            'patient.bloodType',
            'doctor.user',
            'doctor.speciality',
            'consultation',
        ])->findOrFail($appointmentId);

        // Load existing consultation data if it exists
        if ($this->appointment->consultation) {
            $this->diagnosis = $this->appointment->consultation->diagnosis ?? '';
            $this->treatment = $this->appointment->consultation->treatment ?? '';
            $this->notes = $this->appointment->consultation->notes ?? '';
            $prescription = $this->appointment->consultation->prescription ?? [];
            // Ensure each medicine has the duration field
            $this->medicines = array_map(function ($med) {
                return array_merge(['name' => '', 'dose' => '', 'frequency' => '', 'duration' => ''], $med);
            }, $prescription);
        }

        // Ensure at least one empty medicine row
        if (empty($this->medicines)) {
            $this->medicines = [['name' => '', 'dose' => '', 'frequency' => '', 'duration' => '']];
        }

        // Store patient history as plain array
        $this->patientHistory = [
            'blood_type' => $this->appointment->patient->bloodType->name ?? 'No registrado',
            'allergies' => $this->appointment->patient->allergies ?? 'No registradas',
            'chronic_conditions' => $this->appointment->patient->chronic_conditions ?? 'No registradas',
            'surgical_history' => $this->appointment->patient->surgical_history ?? 'No registrados',
            'patient_id' => $this->appointment->patient->id,
        ];
    }

    public function addMedicine()
    {
        $this->medicines[] = ['name' => '', 'dose' => '', 'frequency' => '', 'duration' => ''];
    }

    public function removeMedicine($index)
    {
        unset($this->medicines[$index]);
        $this->medicines = array_values($this->medicines);

        if (empty($this->medicines)) {
            $this->medicines = [['name' => '', 'dose' => '', 'frequency' => '', 'duration' => '']];
        }
    }

    public function loadPreviousConsultations()
    {
        $patientId = $this->appointment->patient_id;

        $this->previousConsultations = Appointment::where('patient_id', $patientId)
            ->where('id', '!=', $this->appointmentId)
            ->whereHas('consultation')
            ->with(['consultation', 'doctor.user'])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($appt) {
                return [
                    'date' => $appt->date->format('d/m/Y'),
                    'doctor_name' => $appt->doctor->user->name ?? '—',
                    'diagnosis' => $appt->consultation->diagnosis ?? '',
                    'treatment' => $appt->consultation->treatment ?? '',
                ];
            })
            ->toArray();

        $this->showPreviousModal = true;
    }

    // Step 1: validate consulta fields and move to receta tab
    public function saveConsulta()
    {
        $this->validate([
            'diagnosis' => 'required|string|min:3',
            'treatment' => 'required|string|min:3',
            'notes' => 'nullable|string',
        ]);

        $this->activeTab = 'receta';
    }

    // Step 2: save full consultation with medicines and redirect
    public function saveReceta()
    {
        $this->validate([
            'diagnosis' => 'required|string|min:3',
            'treatment' => 'required|string|min:3',
            'notes' => 'nullable|string',
            'medicines.*.name' => 'nullable|string',
            'medicines.*.dose' => 'nullable|string',
            'medicines.*.frequency' => 'nullable|string',
            'medicines.*.duration' => 'nullable|string',
        ]);

        // Filter out empty medicines
        $prescription = array_values(array_filter($this->medicines, function ($med) {
            return !empty($med['name']);
        }));

        Consultation::updateOrCreate(
            ['appointment_id' => $this->appointmentId],
            [
                'appointment_id' => $this->appointmentId,
                'diagnosis' => $this->diagnosis,
                'treatment' => $this->treatment,
                'notes' => $this->notes ?: null,
                'prescription' => $prescription ?: null,
            ]
        );

        // Mark appointment as completed
        $this->appointment->update(['status' => Appointment::STATUS_COMPLETED]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Consulta guardada!',
            'text' => 'La consulta médica se ha registrado correctamente.',
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}

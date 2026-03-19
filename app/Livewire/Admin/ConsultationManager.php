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

    // Medications (prescription)
    public $medications = [];

    // UI state
    public $activeTab = 'consulta';
    public $showHistoryModal = false;
    public $showPreviousConsultationsModal = false;

    // Previous consultations data
    public $previousConsultations = [];

    // Patient history for modal (stored as array to survive Livewire dehydration)
    public $patientHistory = [];

    protected $rules = [
        'diagnosis' => 'required|string|min:3',
        'treatment' => 'required|string|min:3',
        'notes' => 'nullable|string',
        'medications.*.name' => 'nullable|string',
        'medications.*.dose' => 'nullable|string',
        'medications.*.frequency' => 'nullable|string',
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
            $this->medications = $this->appointment->consultation->prescription ?? [];
        }

        // Ensure at least one empty medication row
        if (empty($this->medications)) {
            $this->medications = [['name' => '', 'dose' => '', 'frequency' => '']];
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

    public function addMedication()
    {
        $this->medications[] = ['name' => '', 'dose' => '', 'frequency' => ''];
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);

        if (empty($this->medications)) {
            $this->medications = [['name' => '', 'dose' => '', 'frequency' => '']];
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
            ->toArray();

        $this->showPreviousConsultationsModal = true;
    }

    public function saveConsultation()
    {
        $this->validate();

        // Filter out empty medications
        $prescription = array_filter($this->medications, function ($med) {
            return !empty($med['name']);
        });

        $consultationData = [
            'appointment_id' => $this->appointmentId,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes ?: null,
            'prescription' => array_values($prescription) ?: null,
        ];

        Consultation::updateOrCreate(
            ['appointment_id' => $this->appointmentId],
            $consultationData
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\BloodType;


class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with(['user', 'bloodType'])->get();
        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();

        // Define which fields belong to each tab to detect validation errors
        $errorGroups = [
            'antecedentes'        => ['allergies', 'chronic_conditions', 'surgical_history', 'family_history'],
            'informacion-general' => ['blood_type_id', 'observations'],
            'contacto-emergencia' => ['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'],
        ];

        // Default tab
        $initialTab = 'datos-personales';

        // If there are validation errors, open the tab that contains them
        $errors = session('errors') ?? new \Illuminate\Support\MessageBag();
        foreach ($errorGroups as $tabName => $fields) {
            if ($errors->hasAny($fields)) {
                $initialTab = $tabName;
                break;
            }
        }

        return view('admin.patients.edit', compact('patient', 'bloodTypes', 'initialTab', 'errorGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
    $data = $request->validate([
        'blood_type_id' => 'nullable|exists:blood_types,id',
        'allergies' => 'nullable|string|min:3|max:255',
        'chronic_conditions' => 'nullable|string|min:3|max:255',
        'surgical_history' => 'nullable|string|min:3|max:255',
        'family_history' => 'nullable|string|min:3|max:255',
        'observations' => 'nullable|string|min:3|max:255',
        'emergency_contact_name' => 'nullable|string|min:3|max:255',
        'emergency_contact_phone' => ['nullable', 'string', 'max:12', 'min:10'],
        'emergency_contact_relationship' => 'nullable|string|min:3|max:50',
    ]);

    $patient->update($data);

    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Paciente actualizado',
        'text' => 'El paciente ha sido actualizado exitosamente'
    ]);

    return redirect()->route('admin.patients.edit', $patient)->with('success', 'Patient updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
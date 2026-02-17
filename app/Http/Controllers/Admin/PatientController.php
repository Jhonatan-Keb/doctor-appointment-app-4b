<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodTypes = BloodType::pluck('name', 'id')->toArray();
        return view('admin.patients.create', compact('bloodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'id_number' => 'required|string|unique:users,id_number|max:20',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string|max:500',

            'blood_type_id' => 'nullable|exists:blood_types,id',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'surgical_history' => 'nullable|string',
            'family_history' => 'nullable|string',
            'observations' => 'nullable|string',

            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_number' => $validated['id_number'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'address' => $validated['address'],
        ]);

        // Asignar rol de Paciente
        $user->assignRole('Paciente');

        // Crear el registro de paciente
        Patient::create([
            'user_id' => $user->id,
            'blood_type_id' => $validated['blood_type_id'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'chronic_conditions' => $validated['chronic_conditions'] ?? null,
            'surgical_history' => $validated['surgical_history'] ?? null,
            'family_history' => $validated['family_history'] ?? null,
            'observations' => $validated['observations'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
        ]);

        return redirect()->route('admin.patients.index')
            ->with('swal', [
                'title' => 'Paciente creado',
                'text' => 'El paciente ha sido creado exitosamente.',
                'icon' => 'success',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient->load(['user', 'bloodType']);
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $patient->load(['user', 'bloodType']);
        $bloodTypes = BloodType::pluck('name', 'id')->toArray();
        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * ✅ FIX: Este update SOLO actualiza datos del PACIENTE,
     * porque en tu edit.blade.php los datos de usuario (name/email/phone/address...)
     * se editan desde "Editar usuario ↗" y aquí van solo en modo lectura.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'surgical_history' => 'nullable|string',
            'family_history' => 'nullable|string',
            'observations' => 'nullable|string',

            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
        ]);

        $patient->update($validated);

        return redirect()->route('admin.patients.index')
            ->with('swal', [
                'title' => 'Paciente actualizado',
                'text' => 'El paciente ha sido actualizado exitosamente.',
                'icon' => 'success',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        // Eliminar el usuario (el paciente se eliminará en cascada)
        $patient->user->delete();

        return redirect()->route('admin.patients.index')
            ->with('swal', [
                'title' => 'Paciente eliminado',
                'text' => 'El paciente ha sido eliminado exitosamente.',
                'icon' => 'success',
            ]);
    }
}

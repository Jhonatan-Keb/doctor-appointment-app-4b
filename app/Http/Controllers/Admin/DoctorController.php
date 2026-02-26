<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.doctors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('Doctor')
            ->whereDoesntHave('doctor')
            ->get();
        $specialities = Speciality::all();
        return view('admin.doctors.create', compact('users', 'specialities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:doctors,user_id',
            'speciality_id' => 'nullable|exists:specialities,id',
            'medical_license_number' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
        ]);

        $doctor = Doctor::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Doctor creado!',
            'text' => 'El doctor se ha creado correctamente.',
        ]);

        return redirect()->route('admin.admin.doctors.edit', $doctor);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        $specialities = Speciality::all();
        return view('admin.doctors.edit', compact('doctor', 'specialities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'speciality_id' => 'nullable|exists:specialities,id',
            'medical_license_number' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
        ]);
        $doctor = Doctor::findOrFail($id);
        $doctor->update($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Doctor actualizado!',
            'text' => 'Los datos del doctor se han actualizado correctamente.',
        ]);
        return redirect()->route('admin.admin.doctors.edit', $doctor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

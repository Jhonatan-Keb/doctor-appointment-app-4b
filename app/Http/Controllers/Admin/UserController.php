<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9\-]+$/|unique:users',
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create($data);
        $user->roles()->attach($data['role_id']);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado',
            'text' => 'El usuario ha sido creado exitosamente'
        ]);
        //Si el usuario creado es un paciente, crear registro de paciente
        if ($user->hasRole('Paciente')) {
            $patient = $user->patient()->create([]);
            return redirect()->route('admin.admin.patients.edit', $patient);
        }

        //Si el usuario creado es un doctor, crear registro de doctor
        if ($user->hasRole('Doctor')) {
            $doctor = $user->doctor()->create([]);
            return redirect()->route('admin.admin.doctors.edit', $doctor);
        }

        return redirect()->route('admin.admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // Change required to nullable
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9\-]+$/|unique:users,id_number,' . $user->id,
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);
        unset($data['password']);

        $user->update($data);

        //si el usuario quiere cambiar su contraseña que la cambie, si no ño

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }
        $user->roles()->sync($data['role_id']);
        $user->refresh(); // reload roles after sync

        // If the user was switched to Doctor, create the doctor profile if missing
        if ($user->hasRole('Doctor') && !$user->doctor) {
            $doctor = $user->doctor()->create([]);
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Usuario actualizado',
                'text' => 'Rol cambiado a Doctor. Completa el perfil médico.',
            ]);
            return redirect()->route('admin.admin.doctors.edit', $doctor);
        }

        // If the user was switched to Paciente, create the patient profile if missing
        if ($user->hasRole('Paciente') && !$user->patient) {
            $patient = $user->patient()->create([]);
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Usuario actualizado',
                'text' => 'Rol cambiado a Paciente. Completa el perfil del paciente.',
            ]);
            return redirect()->route('admin.admin.patients.edit', $patient);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado',
            'text' => 'El usuario ha sido actualizado exitosamente',
        ]);
        return redirect()->route('admin.admin.users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            abort(403);
        }

        //Eliminar roles asociados a un usuario
        $user->roles()->detach();

        //Eliminar el usuario
        $user->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado',
            'text' => 'El usuario ha sido eliminado exitosamente'
        ]);

        return redirect()->route('admin.admin.users.index');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient; // Añadido según el diff
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_numero' => 'required|string|max:255|unique:users',
            'phone' => 'required|digits_between:7,15',
            'role' => 'required|exists:roles,id',
            'address' => 'required|min:3|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_numero' => $request->id_numero,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Asignar el rol al usuario (buscar por id y usar su nombre)
        $role = Role::find($request->role);
        if ($role) {
            $user->assignRole($role->name);
        }

        // Lógica agregada: Si el rol (por nombre) es 'Paciente', crear registro en la tabla patients
        if ($role && $role->name === 'Paciente') {
            Patient::create([
                'user_id' => $user->id,
                'emergency_contact_name' => 'Por definir',
                'emergency_contact_phone' => '0000000000',
            ]);

            return redirect()->route('admin.patients.index')
                ->with('swal', [
                    'title' => 'Paciente creado',
                    'text' => 'Complete la información médica del paciente.',
                    'icon' => 'success',
                ]);
        }

        // Redirigir con mensaje de éxito (Actualizado según diff)
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.')
            ->with('swal', [
                'title' => 'Usuario creado',
                'text' => 'El usuario ha sido creado exitosamente.',
                'icon' => 'success',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $role = Role::find($request->role);
        $user->syncRoles([$role->name]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido actualizado exitosamente',
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar tu propio usuario',
            ]);
            return redirect()->route('admin.users.index');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.')
            ->with('swal', [
                'title' => 'Usuario eliminado',
                'text' => 'El usuario ha sido eliminado exitosamente.',
                'icon' => 'success',
            ]);
    }
}
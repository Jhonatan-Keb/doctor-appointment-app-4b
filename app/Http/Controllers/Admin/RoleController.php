<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Usa el modelo de Spatie (no App\Models\Role)
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $guard = config('auth.defaults.guard', 'web');

        $validated = $request->validate([
            // Único por guard (la tabla tiene índice único (name, guard_name))
            'name' => 'required|string|max:100|unique:roles,name,NULL,id,guard_name,' . $guard,
        ]);

        Role::create([
            'name'       => $validated['name'],
            'guard_name' => $guard,
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', '¡Rol creado correctamente!');
    }

    public function show(string $id) { /* opcional */ }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, string $id)
    {
        $guard = config('auth.defaults.guard', 'web');

        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $id . ',id,guard_name,' . $guard,
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name'       => $request->input('name'),
            'guard_name' => $guard, // mantener coherencia
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', '¡Rol actualizado correctamente!');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}

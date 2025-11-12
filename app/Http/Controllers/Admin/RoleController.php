<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /** IDs protegidos: 1..4 */
    protected function isProtected(Role $role): bool
    {
        return (int) $role->id <= 4;
    }

    public function index() { return view('admin.roles.index'); }

    public function create() { return view('admin.roles.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $data['name'], 'guard_name' => 'web']);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Rol creado',
            'text'  => 'El rol se creó correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        if ($this->isProtected($role)) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Este rol no puede modificarse.',
            ]);
            return redirect()->route('admin.roles.index');
        }

        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        if ($this->isProtected($role)) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Este rol no puede modificarse.',
            ]);
            return redirect()->route('admin.roles.index');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        if ($role->name === $request->name) {
            session()->flash('swal', [
                'icon'  => 'info',
                'title' => 'Sin cambios',
                'text'  => 'No se detectaron modificaciones.',
            ]);
            return redirect()->route('admin.roles.edit', $role);
        }

        $role->update(['name' => $request->name]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Rol actualizado',
            'text'  => 'El rol se actualizó correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        if ($this->isProtected($role)) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Este rol no se puede eliminar.',
            ]);
            return redirect()->route('admin.roles.index');
        }

        $name = $role->name;
        $role->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Rol eliminado',
            'text'  => "«{$name}» se eliminó correctamente.",
        ]);

        return redirect()->route('admin.roles.index');
    }
}

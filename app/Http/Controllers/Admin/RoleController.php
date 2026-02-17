<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');  //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Role Created Successfully',
            'text'  => 'El rol ha sido creado correctamente.',
        ]);

        // Opcional: si ya usas swal, puedes quitar este with si no lo necesitas
        return redirect()->route('admin.roles.index');
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
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);

        // Si el nombre es el mismo, no actualizamos y mostramos info
        if ($role->name === $request->name) {
            session()->flash('swal', [
                'icon'  => 'info',
                'title' => 'Sin cambios',
                'text'  => 'No se detectaron modificaciones en el rol.',
            ]);

            return redirect()->route('admin.roles.index');
        }

        // Actualizamos nombre
        $role->update([
            'name' => $request->name,
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Role actualizado',
            'text'  => 'El rol ha sido actualizado correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // ✅ Definimos el rol antes de usarlo
        $role = Role::findOrFail($id);

        if ($role->id <= 4) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'error',
                'text'  => 'No se puede eliminar este rol.',
            ]);

            return redirect()->route('admin.roles.index');
        }

        $role->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Role eliminado',
            'text'  => 'El rol ha sido eliminado correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }
}
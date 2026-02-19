<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

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
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol creado correctamente',
            'text' => 'El rol se ha creado exitosamente.'
        ]);

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
    public function edit(Role $role)
    {
        if($role->id <= 4){
            session()->flash('swal',
            [
                'icon' => 'error',
                'title' => 'No se puede editar el rol',
                'text' => 'No se puede editar el rol por ser un rol por defecto.'
            ]);
            return redirect()->route('admin.roles.index');
        }

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

        // Prevent redundant updates if name hasn't changed
        if ($role->name === $request->name) {
            session()->flash('swal',
            [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'El nombre del rol no ha cambiado.'
            ]);
            return redirect()->route('admin.roles.index');
        }

        $role->update($request->only('name'));

        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol actualizado correctamente',
            'text' => 'El rol se ha actualizado exitosamente.'
        ]);

        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if($role->id <= 4){
            session()->flash('swal',
            [
                'icon' => 'error',
                'title' => 'No se puede eliminar el rol',
                'text' => 'No se puede eliminar el rol por ser un rol por defecto.'
            ]);
            return redirect()->route('admin.roles.index');
        }

        $role->delete();

        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol eliminado correctamente',
            'text' => 'El rol se ha eliminado exitosamente.'
        ]);

        return redirect()->route('admin.roles.index');
    }
}

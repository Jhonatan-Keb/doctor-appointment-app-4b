<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Insurance;

class InsuranceController extends Controller
{
    public function index()
    {
        $insurances = Insurance::all();
        return view('admin.insurances.index', compact('insurances'));
    }

    public function create()
    {
        return view('admin.insurances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:255',
            'notas_adicionales' => 'nullable|string',
        ]);

        Insurance::create($request->all());

        return redirect()->route('admin.admin.insurances.index')
                         ->with('success', 'Aseguradora guardada con éxito.');
    }

    public function show(Insurance $insurance)
    {
        return view('admin.insurances.show', compact('insurance'));
    }

    public function edit(Insurance $insurance)
    {
        return view('admin.insurances.edit', compact('insurance'));
    }

    public function update(Request $request, Insurance $insurance)
    {
        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:255',
            'notas_adicionales' => 'nullable|string',
        ]);

        $insurance->update($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Aseguradora actualizada',
            'text' => 'La aseguradora ha sido actualizada exitosamente'
        ]);

        return redirect()->route('admin.admin.insurances.index')
                         ->with('success', 'Aseguradora actualizada con éxito.');
    }

    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Aseguradora eliminada',
            'text' => 'La aseguradora ha sido eliminada exitosamente'
        ]);

        return redirect()->route('admin.admin.insurances.index');
    }
}

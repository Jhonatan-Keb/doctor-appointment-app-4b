<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Ticket::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'Abierto',
        ]);

        return redirect()->route('admin.admin.tickets.index')
            ->with('swal', [
                'title' => 'Ticket enviado',
                'text' => 'Nuestro equipo de soporte se pondrá en contacto contigo.',
                'icon' => 'success',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.admin.tickets.index')
            ->with('swal', [
                'title' => 'Ticket actualizado',
                'text' => 'El estado del ticket ha sido actualizado.',
                'icon' => 'success',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.admin.tickets.index')
            ->with('swal', [
                'title' => 'Ticket eliminado',
                'text' => 'El ticket ha sido eliminado exitosamente.',
                'icon' => 'success',
            ]);
    }
}
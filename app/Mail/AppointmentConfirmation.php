<?php

namespace App\Mail;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}
    

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Cita Médica',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-confirmation',
        );
    }

    public function attachments(): array
{
    try {
        $pdf = Pdf::loadView('pdf.appointment-receipt', [
            'appointment' => $this->appointment,
        ]);

        return [
            Attachment::fromData(
                fn() => $pdf->output(),
                'comprobante-cita.pdf'
            )->withMime('application/pdf'),
        ];
    } catch (\Throwable $e) {
        \Log::warning('No se pudo generar el PDF del comprobante: ' . $e->getMessage());
        return [];
    }
}
    
}
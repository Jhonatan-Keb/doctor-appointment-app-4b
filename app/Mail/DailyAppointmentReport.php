<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyAppointmentReport extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $appointments,
        public string $recipientName = 'Administrador'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte de Citas del Día — ' . now()->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-report',
        );
    }
}
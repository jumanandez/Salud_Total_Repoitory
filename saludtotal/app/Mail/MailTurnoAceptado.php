<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailTurnoAceptado extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $id_turno;
    public string $doctor;
    public string $fecha;
    public string $hora;
    public string $estado;
    /**
     * Create a new message instance.
     */
    public function __construct($nombre, $id_turno, $doctor, $fecha, $hora, $estado)
    {
        $this->nombre = $nombre;
        $this->id_turno = $id_turno;
        $this->doctor = $doctor; // Asumimos que ya es el nombre completo del doctor
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->estado = ucfirst($estado); // Aseguramos que el estado esté en formato legible
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mail Turno Aceptado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.mail-turno-aceptado',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

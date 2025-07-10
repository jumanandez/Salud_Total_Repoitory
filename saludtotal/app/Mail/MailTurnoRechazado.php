<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailTurnoRechazado extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $turno_id;
    public string $doctor;
    public string $fecha;
    public string $hora;
    public string $estado;
    public string $mensaje; // Mensaje opcional, por defecto indica que el turno ha sido rechazado
    /**
     * Create a new message instance.
     */
    public function __construct($nombre, $turno_id, $doctor, $fecha, $hora, $estado, $mensaje = 'El turno ha sido rechazado')
    {
        $this->mensaje = $mensaje; // Mensaje opcional, por defecto indica que el turno ha sido rechazado';
        $this->nombre = $nombre;
        $this->turno_id = $turno_id;
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
            subject: 'Mail Turno Rechazado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.mail-turno-rechazado',
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

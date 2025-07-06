<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Doctor;
class NotificacionTurno extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $id_turno;
    public string $doctor;
    public string $fecha;
    public string $hora;

    public function __construct($nombre, $id_turno, $doctor, $fecha, $hora)
    {
        $this->nombre = $nombre;
        $this->id_turno = $id_turno;
        $this->doctor = Doctor::find($doctor)->nombre_apellido;
        $this->fecha = $fecha;
        $this->hora = $hora;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación de Turno',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.notificacion-turno',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

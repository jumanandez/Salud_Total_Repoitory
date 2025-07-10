<?php

namespace App\Enum;

enum EstadoSolicitudReprogramacion: string
{
    case PENDIENTE = 'pendiente';
    case ACEPTADO = 'aceptado';
    case RECHAZADO = 'rechazado';
}

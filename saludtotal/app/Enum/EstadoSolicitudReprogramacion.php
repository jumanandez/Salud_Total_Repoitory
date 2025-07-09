<?php

namespace App\Enum;

enum EstadoSolicitudReprogramacion
{
    case PENDIENTE = 'pendiente';
    case ACEPTADO = 'aceptado';
    case RECHAZADO = 'rechazado';
}

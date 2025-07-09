<?php

namespace App\Enum;

enum EstadoTurno: string
{
    case PENDIENTE = 'pendiente';
    case ACEPTADO = 'aceptado';
    case RECHAZADO = 'rechazado';
    case CANCELADO = 'cancelado';
    case ATENDIDO = 'atendido';
    case DESAPROVECHADO = 'desaprovechado';
    case ACTIVO = 'activo';
}

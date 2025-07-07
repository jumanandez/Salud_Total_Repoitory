export async function pedirTurno(e, doctor, fecha, hora, csrfToken) {
    e.preventDefault();
    if(!doctor || !fecha || !hora) {
        return {
            exito: false,
            error: true,
            mensaje: 'Todos los campos son obligatorios',
        };
    }
    const fechaFormateada = fecha.getFullYear() + '-' +
        String(fecha.getMonth() + 1).padStart(2, '0') + '-' +
        String(fecha.getDate()).padStart(2, '0');
    const datos = {
        doctor_id: doctor,
        fecha: fechaFormateada,
        hora: hora,
    };
    console.log(datos);
    try {
        const response = await fetch('/turnos/solicitar-turno', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'application/json',
            },
            body: JSON.stringify(datos),
        });
        const data = await response.json();

        if (!response.ok) {
            if (response.status === 422) {
                // Errores de validación
                return {
                    exito: false,
                    error: true,
                    errores: data.errors,
                    mensaje: 'Errores de validación'
                };
            } else {
                // Otros errores del servidor
                return {
                    exito: false,
                    error: true,
                    mensaje: data.message || 'Error del servidor',
                    codigo: response.status
                };
            }
        }
        // Respuesta exitosa
        return {
            exito: true,
            error: false,
            datos: data.Turno,
            mensaje: data.mensaje || 'Turno creado exitosamente'
        };
    } catch (err) {
        // Error de red o conexión
        return {
            exito: false,
            error: true,
            mensaje: err.message || 'Error de conexión. Verifica tu conexión a internet.',
            errorOriginal: err
        };
    }
}

export async function cancelarTurno(turnoId, csrfToken) {
    try {
        const response = await fetch(`/turnos/${turnoId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'application/json',
            },
        });
        const data = await response.json();
        if (!response.ok) {
            return {
                exito: false,
                error: true,
                mensaje: data.message || 'Error al cancelar el turno',
                codigo: response.status
            };
        }
        return {
            exito: true,
            error: false,
            mensaje: data.message || 'Turno cancelado exitosamente'
        };
    } catch (err) {
        return {
            exito: false,
            error: true,
            mensaje: 'Error de conexión. Verifica tu conexión a internet.',
            errorOriginal: err
        };
    }
}

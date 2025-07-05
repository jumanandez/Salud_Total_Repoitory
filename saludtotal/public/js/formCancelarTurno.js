 document.addEventListener('DOMContentLoaded', function () {
    const form_cancelarTurno = document.getElementById('form-cancelarTurno');
    if (form_cancelarTurno) {
        form_cancelarTurno.addEventListener('submit', async function (e) {
            e.preventDefault();
            const turno_id = JSON.parse(form_cancelarTurno.dataset.turno_id);
            const resultado = await solicitarCancelarTurno(e, turno_id);
            console.log(resultado);
        });
    }

    const form = document.getElementById('form-reprogramar-turno');
    if (form) {

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const turno = JSON.parse(form.dataset.turno);
            const fecha = document.getElementById('input_fecha').value;
            console.log(fecha);
            const hora = document.getElementById('select_nueva_hora').value;
            const resultado = await solicitarReprogramación(turno, fecha, hora);
            console.log(resultado);
        });
    }else {
        console.log('no hay form');
    }
 });
async function solicitarCancelarTurno(e, turno_id) {
    e.preventDefault();
    if (!turno_id)
        {
            console.log('No se ha seleccionado un turno');
            return {
                exito: false,
                error: true,
                mensaje: 'no está el id del turno',
            };
        }
    try {
        const response = await fetch(`/turnos/mis-turnos/solicitar-cancelacion/${turno_id}`,
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Accept: 'application/json',
                },
                body: turno_id,
            }
            );
        const data = await response.json();
        if (!response.ok) {
            return {
                error: true,
                mensaje: data.message || 'Error al cancelar el turno',
                codigo: response.status
            };
        }
        location.reload();
        return {
            exito: true,
            error: false,
            mensaje: data.message || 'Turno cancelado exitosamente',
            turno: data.turno
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
async function solicitarReprogramación(turno, fecha, hora) {
    if (hora == turno.hora && fecha == turno.fecha) {
        return {
            exito: false,
            error: true,
            mensaje: 'La fecha y hora son las mismas del turno',
        };
    }
    try {
        const response = await fetch(`/turnos/mis-turnos/solicitar-reprogramacion`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    turno_id: turno.turno_id,
                    nueva_fecha: fecha,
                    nueva_hora: hora,
                    doctor_id: turno.doctor_id,
                }),
            });
            const data = await response.json();
            if (!response.ok) {
                return {
                    error: true,
                    mensaje: data.message || 'Error al solicitar la reprogramación',
                };
            }
            document.getElementById('resultado-reprogramacion').classList.remove('hidden');
            document.getElementById('resultado-reprogramacion').innerHTML = data.mensaje;
            setTimeout(() => {
                location.reload();
            }, 3000);
            }catch (err) {
                return {
                    exito: false,
                    error: true,
                    mensaje: 'Error de conexión. Verifica tu conexión a internet.',
                    errorOriginal: err
                };
            }

}




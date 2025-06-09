export default async function cargarTurnosDisponibles(doctorId, fecha) {
    let slots = [];
    const fechaFormateada = fecha.getFullYear() + '-' +
        String(fecha.getMonth() + 1).padStart(2, '0') + '-' +
        String(fecha.getDate()).padStart(2, '0');

    try {
    const response = await fetch(`/turnos/doctor/disponibles?doctor_id=${doctorId}&fecha=${fechaFormateada}`,
        {
            headers: {
                'Accept': 'application/json'
            },
        }
    );

    if (!response.ok) {
        const errorData = await response.json();
        const mensaje = errorData?.errors?.fecha?.[0] || 'Error desconocido';
        console.log(mensaje);

        return {error: mensaje};
    }
    slots = await response.json();
    } catch (error) {
        console.error("Error al cargar los slots disponibles:", error.message);
        return {error: 'Error al cargar los slots disponibles'};
    }
    return {data: slots};
}

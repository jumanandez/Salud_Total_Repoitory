document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-reprogramar-turno');
    document.getElementById('boton-reprogramar').addEventListener('click', async function() {
        const modal = document.getElementById('centeredFormModal');
        if (modal.classList.contains('modal-is-open')) {
            cargarHorarios(form.dataset.doctor_id).then(data => {
                const filteredData = data.filter(item => item.hora_inicio).map(item => item.dia).join(', ');
                document.getElementById('label-fecha').textContent = 'Fecha: ' + filteredData;
            });
            const select = document.getElementById('select_nueva_hora');
            fillSelect(select, form.dataset.doctor_id, form.dataset.fecha);
            }
        });
    document.getElementById("input_fecha").addEventListener("change", function() {
        const select = document.getElementById('select_nueva_hora');
        select.options.length = 0;
        fillSelect(select, form.dataset.doctor_id, this.value);
    });
});
async function fillSelect(select, doctor_id, fecha_str) {
    const fecha = fecha_str.includes(' ') ? new Date(fecha_str) : new Date(fecha_str + ' 00:00:00');
    const result = await cargarTurnosDisponibles(doctor_id, fecha);
    console.log(result);
        if (!result.data) {
            const option = document.createElement('option');
            option.value = 'No hay disponibles';
            option.textContent = result.error? result.error : 'No hay disponibles';
            option.disabled = true;
            select.appendChild(option);
        }
        result.data?.forEach(item => {
            const option = document.createElement('option');
            option.value = item; // o item.hora, depende tu API
            option.textContent = item; // o item.descripcion
            select.appendChild(option);
        });
}
async function cargarTurnosDisponibles(doctorId, fecha) {
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
    const data = await response.json();
    slots = data.slots;
    } catch (error) {
        console.error("Error al cargar los slots disponibles:", error.message);
        return {error: 'Error al cargar los slots disponibles'};
    }
    return {data: slots};
}
const cargarHorarios = async (doctor_id) => {

    const response = await fetch(`/profesionales/${doctor_id}/horarios`);
    console.log(response);
    if (!response.ok) {
        throw new Error('Error al cargar los horarios');
    }
    const dataHoras = await response.json();
    const horarios = dataHoras.horarios_laborales;
    console.log(horarios);

    const dias_semana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];


    let horariosParaComponente = [];
    dias_semana.forEach( (dia, index) => {
        if(horarios.some(h => h.dia_semana - 1 === index)) {
            horariosParaComponente.push({
                horario_id : horarios.find(h => h.dia_semana - 1 === index).id,
                doctor_id: horarios.find(h => h.dia_semana - 1 === index).doctor_id,
                dia_num : index + 1,
                dia,
                hora_inicio: horarios.find(h => h.dia_semana - 1 === index).hora_inicio,
                hora_fin: horarios.find(h => h.dia_semana - 1 === index).hora_fin
            })
        }else {
            horariosParaComponente.push({
                horario_id : null,
                doctor_id: doctor_id,
                dia_num : index + 1,
                dia,
                hora_inicio: null,
                hora_fin: null
            });
        }
    });

    return horariosParaComponente;

}

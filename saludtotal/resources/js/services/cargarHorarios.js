
export const cargarHorarios = async (doctor_id) => {

    const response = await fetch(`/profesionales/${doctor_id}/horarios`);
    if (!response.ok) {
        throw new Error('Error al cargar los horarios');
    }
    const horarios = await response.json();
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
    console.log(horariosParaComponente);
    return horariosParaComponente;

}


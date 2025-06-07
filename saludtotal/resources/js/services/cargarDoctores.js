export const cargarDoctores = async (especialidadId = null) => {
    try {
        let url = '/profesionales/doctores';
        if (especialidadId !== null) {
            url = `/profesionales/especialidades/${especialidadId}/doctores`;
        }
        const response = await fetch(url);
        const data = await response.json();

        return data;
    } catch (error) {
        console.error('Error fetching doctores:', error);
    }
}
export const cargarDoctorById = async (doctorId) => {
    try {
        const response  = await fetch(`/profesionales/doctor/${doctorId}`);
        const data = await response.json();

        return data;
    } catch (error) {
        return 'Error al cargar el doctor';
    }
}

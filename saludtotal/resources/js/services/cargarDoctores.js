export const cargarDoctores = async (especialidadId) => {
    try {
        const response = await fetch(`/profesionales/especialidades/${especialidadId}/doctores`);
        const data = await response.json();

        return data;
    } catch (error) {
        console.error('Error fetching doctores:', error);
    }
}

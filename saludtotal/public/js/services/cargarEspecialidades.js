export const cargarEspecialidades = async () => {

    try {
        const response = await fetch('profesionales/especialidades');
        const data = await response.json();

        return data;
    } catch (error) {
        console.error('Error fetching especialidades:', error);
    }
}


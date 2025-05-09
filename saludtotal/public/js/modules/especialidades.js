    import { cargarEspecialidades } from "../services/cargarEspecialidades.js";

document.addEventListener("DOMContentLoaded", function() {
    renderEspecialidades();
});
async function renderEspecialidades() {
    const especialidadesContainer = document.getElementById("especialidades");

    especialidadesContainer.innerHTML = "";

    let especialidades = await cargarEspecialidades();

    especialidades.forEach(especialidad => {
        const div = document.createElement('div');
        div.className = 'border rounded overflow-hidden';
        div.innerHTML = `
            <button class="w-full bg-grey-dark text-left px-4 py-2 flex justify-between items-center" onclick="toggleSection(this)">
                <span class="text-light text-3xl">${especialidad.nombre}</span>
                <span class="toggle-icon text-blue-600 text-xl">+</span>
            </button>
        `;
        especialidadesContainer.appendChild(div);
    });

}

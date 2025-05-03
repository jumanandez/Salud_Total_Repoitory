document.addEventListener('DOMContentLoaded', () => {
    // Selecciona el modal y el input de fecha y hora
    const modal = document.getElementById('centeredFormModal');
    const datetimeInput = document.getElementById('datetime-input');

    // Agrega un evento para abrir el modal
    document.querySelectorAll('.modal-trigger').forEach(trigger => {
        trigger.addEventListener('click', () => {
            modal.classList.add('open'); // Muestra el modal

            // Espera un pequeño tiempo y enfoca el input
            setTimeout(() => {
                datetimeInput.focus();
                datetimeInput.showPicker();
            }, 100); // Ajusta el tiempo si es necesario
        });
    });

    // Agrega un evento para cerrar el modal
    document.querySelectorAll('.close-modal').forEach(close => {
        close.addEventListener('click', () => {
            modal.classList.remove('open'); // Oculta el modal
        });
    });
});

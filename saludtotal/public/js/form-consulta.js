
document.getElementById('consultaForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Evita recarga

    const form = e.target;
    const formData = new FormData(form);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        response = await fetch('/consulta/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        if (!response.ok) {
            const errorData = await response.json();
            console.log(errorData);
            const mensaje = errorData?.errors?.mensaje?.[0] || 'Error al enviar la consulta';
            document.getElementById('error-container').classList.remove('hidden');
            document.getElementById('mensaje-error').innerText = mensaje;
            setTimeout(() => {
                document.getElementById('error-container').classList.add('hidden');
            }, 5000);
            return;
        }
        const data = await response.json();
        document.getElementById('respuesta-container').classList.remove('hidden');
        document.getElementById('respuesta').innerText = data.mensaje || 'Consulta enviada correctamente';
        setTimeout(() => {
            document.getElementById('respuesta-container').classList.add('hidden');
        }, 5000);

    } catch (error) {
        document.getElementById('error-container').classList.remove('hidden');
        document.getElementById('mensaje-error').innerText = 'Error desconocido';
        setTimeout(() => {
            document.getElementById('error-container').classList.add('hidden');
        }, 5000);
    }
});

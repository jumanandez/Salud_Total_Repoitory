
import React from 'react';
import {createRoot} from 'react-dom/client';
import ToggleEspecialidad from './components/ToggleEspecialidad.jsx';
import { SelectDoctor } from './components/SelectDoctor.jsx';


    const especialidaes = createRoot(document.getElementById('toggle-especialidad'));

    especialidaes.render(
        <ToggleEspecialidad />
    );

    // Solo si el div existe (por si no todos los blades lo tienen)
    const selectedDoctor = document.getElementById('selected-doctor');
    let rootDetails = null;
    if (selectedDoctor) {
        rootDetails = createRoot(selectedDoctor);
    }

    export function renderizarDetalle(dataDoctor) {
        if (rootDetails) {
            rootDetails.render(<SelectDoctor/>);
        }
    }
    // doctorDetails.render(
    //     <SelectDoctor />
    // );




import React from 'react';
import {createRoot} from 'react-dom/client';
import ToggleEspecialidad from './components/ToggleEspecialidad.jsx';


    const especialidaes = createRoot(document.getElementById('toggle-especialidad'));

    especialidaes.render(
        <ToggleEspecialidad />
    );



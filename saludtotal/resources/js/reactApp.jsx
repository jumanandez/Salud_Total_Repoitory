
import React from 'react';
import {createRoot} from 'react-dom/client';
import ToggleEspecialidad from './components/ToggleEspecialidad.jsx';
import { FormContent } from './components/FormContent.jsx';
    //vista disponibilidades
    const especialidades = document.getElementById('toggle-especialidad');

    if(especialidades){
        createRoot(especialidades).render(<ToggleEspecialidad />);
    }


    //vista pedir turno
    const formPedirTurno = document.getElementById('form-pedir-turno');

    if(formPedirTurno){
        createRoot(formPedirTurno).render(<FormContent />);
    }

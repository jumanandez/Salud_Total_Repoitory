import React from "react";
import { useEffect, useState } from "react";
import {format} from 'date-fns';
import {es} from 'date-fns/locale';

import { cargarDoctorById } from "../services/cargarDoctores";
export const DetalleDoctor = ({id, fecha, hora}) => {
    const [nombreDoctor, setNombreDoctor] = useState(null);

    useEffect(() => {
        async function fetchNombreDoctor(id){
            const doctor = await cargarDoctorById(id);
            setNombreDoctor(doctor.nombre_apellido);
        }
        fetchNombreDoctor(id);
    }, []);

    const formatearFecha = (fechaStr) => {
        console.log(fechaStr);
        const fecha = new Date(fechaStr.replace(/\//g, '-'));
        return format(fecha, "dd 'de' MMMM 'de' yyyy", { locale: es });
    };
    return (
        <>
            <div className="space-y-2">
                <h1 className="m-4 border-b">Detalle del Turno</h1>
                <p className="mx-4 mb-2 text-gray-700">
                    <strong>- Doctor: </strong>{nombreDoctor ?? 'error con el nombre'}
                </p>
                <p className="mx-4 mb-2 text-gray-700">
                    <strong>- Fecha : </strong>{formatearFecha(fecha) ?? 'fecha no llegó'}
                </p>
                <p className="mx-4 mb-2 text-gray-700">
                    <strong>- Hora: </strong>{hora ?? 'hora no llegó'}
                </p>
            </div>
        </>
)};

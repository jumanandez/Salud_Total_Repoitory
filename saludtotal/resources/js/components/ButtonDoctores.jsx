import React from "react";
import {useEffect, useState } from "react";
import { cargarDoctores } from "../services/cargarDoctores.js";
import SelectDoctor from "./SelectDoctor.jsx";


export function ButtonDisplayDoctores({isOpen ,especialidadId, nombre, handleToggle}) {

    const [doctores, setDoctores] = useState([]);

    useEffect(() => {
        async function fetchData() {
            const data = await cargarDoctores(especialidadId);
            setDoctores(data);
        }
        fetchData();

    }, []);

    return (
        <>
        <button onClick={() => handleToggle(especialidadId)} className="w-full bg-grey rounded-sm text-left px-4  flex justify-between items-center shadow-md">
            <span className="text-wrap text-3xl">{nombre}</span>
            <span className="toggle-icon text-blue-800 text-4xl flex items-center"><i className={isOpen ? "fa-solid fa-minus" : "fa-regular fa-plus"}></i></span>
        </button>

        {isOpen && doctores.map((doctor) => (
            <SelectDoctor key={doctor.doctor_id} doctor_id={doctor.doctor_id} nombre_apellido={doctor.nombre_apellido }/>
        ))}


        </>
    )
}

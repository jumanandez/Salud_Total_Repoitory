import React, { use } from "react";
import {useEffect, useState } from "react";
import { SelectDoctor } from "./SelectDoctor";
import { cargarDoctores } from "../services/cargarDoctores.js";



export function ButtonDisplayDoctores({isOpen ,especialidadId, nombre, handleToggle}) {

    const [doctores, setDoctores] = useState([]);

    useEffect(() => {
        async function fetchData() {
            const data = await cargarDoctores(especialidadId);
            console.log(data);

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

        {isOpen && (
            <div>
        {
            doctores.map((doctor) => (
                <div key={doctor.doctor_id} className="bg-gray-100 p-2 space-y-1">
                    <div className="cursor-pointer px-3 py-1 bg-blue-100 rounded hover:bg-blue-200">
                        {doctor.nombre_apellido}
                    </div>
                </div>
            ))}
        </div>
        )}
        </>
    )
}

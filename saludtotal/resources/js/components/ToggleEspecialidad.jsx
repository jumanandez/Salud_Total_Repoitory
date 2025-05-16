import React, { use } from "react";
import {useEffect, useState } from "react";
import { cargarEspecialidades } from "../services/cargarEspecialidades.js";
import { ButtonDisplayDoctores } from "./ButtonDoctores.jsx";



export default function ToggleEspecialidad () {

    const [openId, setOpenDoctores] = useState(null);

    const handleToggle = (id) => {
        setOpenDoctores((prev) => (prev === id ? null : id));
    }

    const [especialidades, setEspecialidades] = useState([]);

    useEffect(() => {
        async function fetchData() {
            const data = await cargarEspecialidades();
            setEspecialidades(data);
        }
        fetchData();
    }, []);
    return (
        <>
            {
            especialidades.map((especialidad) => (
                <div key={especialidad.especialidad_id} className="rounded-sm overflow-hidden p-2">
                    <ButtonDisplayDoctores isOpen={especialidad.especialidad_id === openId} handleToggle={handleToggle}  especialidadId={especialidad.especialidad_id} nombre={especialidad.nombre} />
                </div>
            ))
            }
        </>
    )

}

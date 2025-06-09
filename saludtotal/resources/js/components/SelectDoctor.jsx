import React, { use } from "react";
import {useEffect, useState} from "react";
import { cargarHorarios } from "../services/cargarHorarios.js";
import Modal from "./Modal.jsx";
import TableHorarios from "./TableHorarios.jsx";
export default function SelectDoctor({doctor_id, nombre_apellido}) {

    const [horarios, setHorarios] = useState([]);
    useEffect(() => {
        async function fetchHorarios(doctor_id) {
            const response = await cargarHorarios(doctor_id);
            setHorarios(response);
            console.log(response);
        }
        fetchHorarios(doctor_id);
    },[]);

    const [isModalOpen, setIsModalOpen] = useState(false);
    const openModal = () => {
        setIsModalOpen(true);
    };
    const closeModal = () => {
        setIsModalOpen(false);
    };


    return (
        <>
            <div onClick={openModal} className="cursor-pointer px-3 py-3 bg-blue-100 rounded hover:bg-blue-200">
                {nombre_apellido}
            </div>
            <Modal
            isOpen={isModalOpen}
            onClose={closeModal}
            title='Horarios de atención'
            size='lg'>
                <TableHorarios nombre_apellido={nombre_apellido} horarios={horarios}/>
            </Modal>
        </>
        );
}



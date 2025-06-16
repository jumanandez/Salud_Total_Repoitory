import React from "react";
import { useState, useEffect } from "react";
import { cargarEspecialidades } from "../services/cargarEspecialidades";
import { CardEspecialidad } from "./cardEspecialidad.jsx";
import { cargarDoctores } from "../services/cargarDoctores.js";
import { InputSelect } from "./FormInputs.jsx";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import { getDay } from "date-fns";
import { registerLocale, setDefaultLocale } from "react-datepicker";
import { es } from "date-fns/locale/es";
import cargarTurnosDisponibles from "../services/cargarTurnosDisponibles.js";
import { cargarHorarios } from "../services/cargarHorarios.js";
import TableHorarios from "./TableHorarios.jsx";
import { pedirTurno } from "../services/enviarFormPedirTurno.js";
import Modal from "./Modal.jsx";
import { DetalleDoctor } from "./DetalleTurno.jsx";
import { AlertError } from "./AlertError.jsx";

export const FormContent = () => {


    const [especialidades, setEspecialidades] = useState([]);
    const [doctores, setDoctores] = useState([]);
    //Renderizado Inicial de especialidades y doctores
    useEffect(() => {
        const fetchEspecialidades = async () => {
            const especialidades = await cargarEspecialidades();
            setEspecialidades(especialidades);
        };
        fetchEspecialidades();

        cargarDoctores().then(setDoctores);
    }, []);


    //Handle al seleccionar especialidad para filtrar doctores
    const [especialidadSeleccionada, setEspecialidadSeleccionada] =
        useState(null);
    useEffect(() => {
        const fetchDoctores = async (especialidadSeleccionada) => {
            const doctores = await cargarDoctores(especialidadSeleccionada);
            setDoctores(doctores);
        };
        fetchDoctores(especialidadSeleccionada);
    }, [especialidadSeleccionada]);
    const handleSelectEspecialidad = (id) => {
        setEspecialidadSeleccionada((prev) => (prev === id ? null : id));
    };


    //Handle al seleccionar doctor y fecha para mostrar horas disponibles
    const [fecha, setFecha] = useState();
    const [doctorSeleccionado, setDoctorSeleccionado] = useState(null);
    const [horariosDisponibles, setHorariosDisponibles] = useState(null);
    const [horaSeleccionada, setHoraSeleccionada] = useState("");
    const [diasLaborales, setDiasLaborales] = useState([]);
    useEffect(() => {
        const fetchHorasDisponibles = async (doctorSeleccionado, fecha) => {
            const dataDiasLaborales = await cargarHorarios(doctorSeleccionado);
            setDiasLaborales(dataDiasLaborales);
            if (doctorSeleccionado && fecha) {
                const dataTurnos = await cargarTurnosDisponibles(
                    doctorSeleccionado,
                    fecha
                );
                if (dataTurnos.error) {
                    setHorariosDisponibles([dataTurnos.error]);
                    return;
                }
                setHorariosDisponibles(dataTurnos.data);
            }
        };
        fetchHorasDisponibles(doctorSeleccionado, fecha);
    }, [doctorSeleccionado, fecha]);
    function handleDoctorSeleccionado(event) {
        setDoctorSeleccionado(event.target.value);
    }
    function isLaborable(date) {
        const day = getDay(date);
        return diasLaborales.some((dia) => dia.dia_num === day && dia.hora_inicio !== null);
    };


    //Handle al enviar el formulario
    const openModal = () => {
        setIsModalOpen(true);
    };
    const closeModal = () => {
        setModalInfo(null);
        setErrores([]);
        setMensaje("");
        setIsModalOpen(false);
    };
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [modalInfo, setModalInfo] = useState(null);
    const [errores, setErrores] = useState([]);
    const [mensaje, setMensaje] = useState("");
    const handleFormSubmit = async (event) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
        const resultado = await pedirTurno(
            event,
            doctorSeleccionado,
            fecha,
            horaSeleccionada,
            csrfToken
        ).then((resultado)=> {
            if (resultado.exito) {
                setModalInfo(resultado.datos); // para mostrar en el modal
                setMensaje(resultado.mensaje); // mensaje de éxito
                openModal();
            } else if (resultado.errores) {
                console.log(resultado.errores);
                setErrores(resultado.errores); // errores por campo
                setMensaje(resultado.mensaje); // mensaje de error general
            } else {
                setMensaje(resultado.mensaje); // mensaje general de error
                console.log('Error grande: ' + mensaje);
            }
        });
    };


    return (
        <>
            <div className="flex flex-col mb-6 justify-center items-center">
                <h1 className="text-bold text-grey-darkest">
                    Seleccione una Especialidad
                </h1>
                <div className="flex flex-wrap justify-center">
                    {especialidades.map((especialidad) => (
                        <div
                            key={especialidad.especialidad_id}
                            onClick={() =>
                                handleSelectEspecialidad(
                                    especialidad.especialidad_id
                                )
                            }
                        >
                            <CardEspecialidad
                                isSelected={
                                    especialidadSeleccionada ===
                                    especialidad.especialidad_id
                                }
                                especialidad_nombre={especialidad.nombre}
                                especialidad_id={especialidad.especialidad_id}
                                icon="../img/doctor-icon.svg"
                            />
                        </div>
                    ))}
                </div>
            </div>
            <div className="flex flex-row mx-3">
                <div className="mb-6 w-1/2">
                    <div className="mb-6 w-full ">
                        <InputSelect
                            width="w-full"
                            label="Doctor"
                            inputId="doctor"
                            name="doctor"
                            value={doctorSeleccionado}
                            onChange={handleDoctorSeleccionado}
                            placeHolder={
                                especialidadSeleccionada
                                    ? "Seleccione un doctor"
                                    : "Seleccione una especialidad"
                            }
                        >
                            {doctores &&
                                doctores.map((doctor) => (
                                    <option
                                        key={doctor.doctor_id}
                                        value={doctor.doctor_id}
                                    >
                                        {doctor.nombre_apellido}
                                    </option>
                                ))}
                        </InputSelect>
                        {errores.doctor &&  (
                            <span className="text-sm text-red-600">
                                {errores.doctor[0]}
                            </span>
                        )}
                    </div>
                    <div className="flex flex-row mb-6 w-full items-stretch">
                        <div className="mb-6 w-1/2">
                            <label className="block text-grey-darkest font-bold mb-1 md:mb-0 pl-1 pr-4 pb-2">
                                Fecha
                            </label>
                            <DatePicker
                                required
                                dateFormat="dd 'de' MMMM 'de' yyyy"
                                placeholderText="Seleccione una fecha"
                                locale={es}
                                minDate={new Date()}
                                selected={fecha}
                                onChange={(date) => setFecha(date)}
                                filterDate={isLaborable}
                                className={`w-full !block px-3 py-3 border ${errores.fecha ? 'border-red-600' : 'border-grey'} rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-grey focus:border-grey`}
                                wrapperClassName="w-full py-3"
                            />
                            {errores.fecha && (
                                <span className="text-xs text-red-600">{errores.fecha[0]}</span>
                            )}
                        </div>
                        <div className="mb-6 w-1/2 pl-1">
                            <InputSelect
                                width="w-full"
                                label="Hora"
                                inputId="hora"
                                name="hora"
                                value={horaSeleccionada}
                                errorBorder = {errores.hora ? 'border-red-600' : null}
                                onChange={(e) =>
                                    setHoraSeleccionada(e.target.value)
                                }
                                placeHolder="Seleccione una hora"
                            >
                                {horariosDisponibles && typeof horariosDisponibles !== "string" &&
                                    horariosDisponibles.map((hora) => (
                                        <option key={hora} value={hora}
                                            onClick={() => setHoraSeleccionada(hora) }>
                                            {hora}
                                        </option>
                                    ))}
                            </InputSelect>
                            {errores.hora && (
                                <span className="text-xs text-red-600">{errores.hora[0]}</span>
                            )}
                        </div>
                    </div>
                    <div className="flex justify-end w-full">
                        <button className="bg-green-500 hover:bg-green-dark text-gray-900 font-semibold py-2 px-4 border rounded-full"
                            onClick={handleFormSubmit}
                        >
                            Pedir Turno
                        </button>
                    </div>
                </div>
                <div className="mb-6 w-1/2">
                    <div className="mb-6 w-full ">
                        {diasLaborales && (
                            <div className="p-4">
                                <TableHorarios horarios={diasLaborales} />
                            </div>
                        )}
                    </div>
                    {mensaje && 
                        typeof errores === 'object' && errores !== null && 
                        typeof modalInfo === 'object' && modalInfo !== null && 
                        !Object.keys(errores).length && 
                        !Object.keys(modalInfo).length && (
                        <AlertError mensaje={mensaje}/>
                    )}
                </div>
            </div>
            {modalInfo && (
                <Modal
                    isOpen={isModalOpen}
                    onClose={closeModal}
                    title={mensaje ?? "Información del Turno"}
                    titleBackground = 'bg-green-lightest'
                    titleColor="text-green-dark"
                >
                    <DetalleDoctor id={modalInfo.doctor_id} fecha={modalInfo.fecha} hora={modalInfo.hora}/>
                    <div className="pt-4 text-right">
                        <button type="button" className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            onClick={()=> {window.location.assign('/dashboard')}}>
                            Volver
                        </button>
                    </div>
                </Modal>
            )}
        </>
    );
};

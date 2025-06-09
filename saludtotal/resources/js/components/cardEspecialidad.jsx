import React from "react";
import { useState, useEffect } from "react";

export const CardEspecialidad = ({isSelected,especialidad_nombre, especialidad_id, icon }) => {

    const bgColor = isSelected ? "bg-blue-200" : "bg-white";
    return (
        <div className={`${bgColor} border-2 border-blue-900 rounded-lg shadow p-2 flex flex-col items-center w-40 mx-3 hover:bg-blue-200 cursor-pointer transition duration-300`}>
            <img src={icon} alt="icon doctor"
                className="w-16 h-16 md:w-14 md:h-14 sm:w-12 sm:h-12"/>
            <p className="mt-2 text-center text-lg md:text-base sm:text-sm">{especialidad_nombre}</p>
        </div>
    )
}

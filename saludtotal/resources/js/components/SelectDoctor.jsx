import React from "react";
import {useEffect, useState } from "react";

export function SelectDoctor({selected}) {

    let classes = "hidden bg-gray-100 p-2 space-y-1";
    if (selected) {
        classes = "bg-gray-100 p-2 space-y-1";
    }
    // return (
    //     <>
    //     <div className= {classes}>
    //         <div class="cursor-pointer px-3 py-1 bg-blue-100 rounded hover:bg-blue-200" onclick="selectDoctor(this)">
    //             {{ $profesional->nombre_apellido }}
    //         </div>
    //     </div>
    //     </>
    // )
}

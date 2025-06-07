import React from "react";

export const AlertError = ({mensaje}) => {

    return (
        <>
            <div className="shadow-lg bg-red-vibrant border-l-8 hover:bg-red-vibrant-dark border-red-vibrant-dark mb-2 p-2 mx-2">
                <div className="p-4 flex flex-col">
                    <p className="text-white text-2xl font-bold">
                        Error
                    </p>
                    <p className="text-white text-sm font-light">
                        {mensaje}
                    </p>
                </div>
            </div>
        </>
    )
}

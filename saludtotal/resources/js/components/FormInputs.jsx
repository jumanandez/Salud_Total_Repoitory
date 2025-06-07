import React from "react";
import { useState, useEffect } from "react";

export const InputSelect = ({width, label,inputId, value,onChange, errorBorder, name,placeHolder, children }) => {
    return (
        <div className={width}>
            <label htmlFor={inputId} className="block text-grey-darkest font-bold mb-1 md:mb-0 pl-1 pr-4 pb-2">
                {label}
            </label>
            <div className="relative">
                <select className={`block appearance-none w-full bg-grey-200 border ${errorBorder ?? 'border-grey'} text-grey-darker py-3 px-4 pr-8 cursor-pointer rounded leading-tight focus:outline-none focus:bg-white focus:${errorBorder ?? 'border-grey'}`}
                id={inputId }
                name={name}
                value={value ?? ''}
                onChange={onChange}
                required
                >
                <option value= '' disabled> {placeHolder}</option>
                    {children}
                </select>
                <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-grey-darker">
                    <svg className="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                    </svg>
                </div>

            </div>
        </div>

    );
}

import React, { useState, useEffect, useRef } from 'react';

export default function Modal ({ isOpen, onClose, title, children, size = "md" }){
    const modalRef = useRef(null);

  // Cierra el modal al hacer clic fuera de él
    useEffect(() => {
    const handleClickOutside = (event) => {
        if (modalRef.current && !modalRef.current.contains(event.target) &&
            event.target.classList.contains('overlay')) {
            onClose();
        }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
        document.removeEventListener('mousedown', handleClickOutside);
    };
    }, [onClose]);


    return (
    <div
        className={`fixed inset-0 flex items-center justify-center z-50 transition-all duration-300 ${
        isOpen ? 'opacity-100 visible' : 'opacity-0 invisible'}`}>
        <div className="overlay absolute inset-0 bg-grey-darkest opacity-75"></div>
        <div
            ref={modalRef} className={`bg-white rounded-lg shadow-xl z-60 relative w-11/12 ${
            size === 'lg' ? 'max-w-4xl' : 'max-w-lg' }`}>
            <div className="p-5">
                <div className="border-b p-2 pb-3 pt-0 mb-4">
                    <div className="flex justify-between items-center">
                        <div>{title}</div>
                        <span className="cursor-pointer px-3 py-1 rounded-full bg-grey-lighter hover:bg-grey-light transition-colors"
                            onClick={onClose}
                        >
                            <span className="text-grey-darkest">
                                <i className="fas fa-times"></i>
                            </span>
                        </span>
                    </div>
                </div>
                {children}
            </div>
        </div>
    </div>
);
};


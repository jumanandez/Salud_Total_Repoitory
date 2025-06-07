export default function TableHorarios({nombre_apellido = false, horarios}) {
    return (
        <>
        <div className="overflow-x-auto">
                <table className="min-w-full border border-grey-darkest rounded-lg overflow-hidden">
                    <thead className="bg-grey-light">
                        <tr>
                            {nombre_apellido && <th className="px-4 py-2 border-b"></th>}

                            {horarios.map((horario, index) => (
                                <th key={index} className="px-4 py-2 border-b text-center">{horario.dia}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            {nombre_apellido &&
                                <td className="px-4 py-2 border-b  font-semibold text-left">{nombre_apellido}</td>
                            }
                            {horarios.map((horario, index)=> (
                                <td key={index} className="px-4 py-2 border-b text-center">
                                    {horario.hora_inicio ?? ' '} - {horario.hora_fin ?? ' '}
                                </td>
                            ))}
                        </tr>
                    </tbody>
                </table>
            </div>
        </>
    )
}

export default function TableHorarios({nombre_apellido, horarios}) {
    return (
        <>
        <div className="overflow-x-auto">
                <table className="min-w-full border border-gray-300 rounded-lg overflow-hidden">
                    <thead className="bg-gray-100">
                        <tr>
                            <th className="px-4 py-2 border-b"></th>
                            {horarios.map((horario, index) => (
                                <th key={index} className="px-4 py-2 border-b text-center">{horario.dia}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        <tr className="hover:bg-gray-50">
                            <td className="px-4 py-2 border-b font-semibold text-left">{nombre_apellido}</td>
                            {horarios.map((horario, index)=> (
                                <td key={index} className="px-4 py-2 border-b text-center">
                                    {horario.hora_inicio ?? '-'} - {horario.hora_fin ?? '-'}
                                </td>
                            ))}
                        </tr>
                    </tbody>
                </table>
            </div>
        </>
    )
}

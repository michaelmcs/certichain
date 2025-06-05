<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ParticipanteExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //
    }
    public function view(): View
    {
        $sql = DB::select("SELECT
        participante.id_participante,
        participante.id_curso,
        participante.dni,
        participante.nombre,
        participante.apellido,
        participante.correo,
        participante.codigo,
        participante.participo_como,
        curso.nombre as 'curso'
        FROM
        participante
        INNER JOIN curso ON participante.id_curso = curso.id_curso");
        return view('participante.exportParticipante', [
            'participante' => $sql
        ]);
    }
}

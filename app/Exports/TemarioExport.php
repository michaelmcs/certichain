<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class TemarioExport implements FromView
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
        $sql = DB::select('SELECT
        temario.id_temario,
        temario.id_curso,
        temario.tema,
        curso.nombre
        FROM
        temario
        INNER JOIN curso ON temario.id_curso = curso.id_curso');
        return view('temario.exportTemario', [
            'temario' => $sql
        ]);
    }
}

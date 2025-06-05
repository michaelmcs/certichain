<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class CursoExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
    }
    public function view(): View
    {
        $sql = DB::select('select * from curso');
        return view('cursos.exportCurso', [
            'curso' => $sql
        ]);
    }
}

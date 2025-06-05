<?php

namespace App\Http\Controllers;

use App\Exports\TemarioExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TemarioController extends Controller
{
    public function index()
    {
        $datos = DB::select(" SELECT
        temario.id_temario,
        temario.id_curso,
        temario.tema,
        curso.nombre
        FROM
        temario
        INNER JOIN curso ON temario.id_curso = curso.id_curso
         ");
         $curso=DB::select(" select * from curso ");
        return view("temario/listaTemario")->with("datos", $datos)->with("curso", $curso);
    }


    public function create()
    {
        $curso = DB::select(" select * from curso ");
        return view("temario/registroTemario")->with("curso", $curso);
    }

    public function store(Request $request)
    {
        $request->validate([
            "curso" => "required",
            "tema" => "required",
        ]);
        //verificar si ya existe el registro
        $verificar = DB::select("SELECT
        Count(*) AS total,
        temario.id_curso,
        curso.nombre
        FROM
        temario
        INNER JOIN curso ON temario.id_curso = curso.id_curso where temario.id_curso=$request->curso and temario.tema='$request->tema' ");
        foreach ($verificar as $key => $value) {
            if ($value->total >= 1) {
                return back()->with("DUPLICADO", "El tema '$request->tema' del curso '$value->nombre' ya existe");
            }
        }
        //ahora registramos
        try {
            $sql = DB::insert(" insert into temario(id_curso,tema)values(?,?) ", [
                $request->curso,
                $request->tema,
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Temario registrado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al registrar, intente nuevamente");
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            "curso" => "required",
            "tema" => "required",
        ]);
        //verificar si ya existe el registro
        $verificar = DB::select(" SELECT
        Count(*) AS total,
        temario.id_curso,
        curso.nombre
        FROM
        temario
        INNER JOIN curso ON temario.id_curso = curso.id_curso where temario.id_curso=$request->curso and temario.tema='$request->tema' and id_temario!=$id ");
        foreach ($verificar as $key => $value) {
            if ($value->total > 0) {
                return back()->with("DUPLICADO", "El tema '$request->tema' del curso '$value->nombre' ya existe");
            }
        }

        //ahora modificamos
        try {
            $sql = DB::update(" update temario set id_curso=?, tema=? where id_temario=? ", [
                $request->curso,
                $request->tema,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Temario modificado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar, intente nuevamente");
        }
    }

    public function destroy($id)
    {
        try {
            $sql = DB::update(" delete from temario where id_temario=$id ");
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Temario eliminado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }

    public function exportTemario()
    {
        return Excel::download(new TemarioExport, 'temario.xlsx'); //csv
    }
}

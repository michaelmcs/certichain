<?php

namespace App\Http\Controllers;

use App\Exports\CursoExport;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CursoController extends Controller
{

    public function index()
    {
        $datos = Curso::orderBy("id_curso", "desc")->paginate(10);
        return view("cursos/listaCurso")->with("datos", $datos);
    }


    public function create()
    {
        return view("cursos/registroCurso");
    }

    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required|unique:curso,nombre",
            "hora" => "required",
            "descripcion" => "required",
            "inicio" => "required",
            "termino" => "required",
            "estado" => "required",
        ]);
        
        //verificar si ya existe el curso
        $verificar = DB::select("select count(*) as 'total' from curso where nombre='$request->nombre' ");
        foreach ($verificar as $key => $value) {
            if ($value->total >= 1) {
                return back()->with("DUPLICADO", "El curso '$request->nombre' ya existe");
            }
        }

        //ahora registramos
        try {
            $sql = DB::insert(" insert into curso(nombre,horas,descripcion,inicio,termino,estado)values(?,?,?,?,?,?) ", [
                $request->nombre,
                $request->hora,
                $request->descripcion,
                $request->inicio,
                $request->termino,
                $request->estado
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Curso registrado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al registrar, intente nuevamente");
        }
    }


    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "nombre" => "required",
            "estado" => "required",
            "hora" => "required",
            "descripcion" => "required",
            "inicio" => "required",
            "termino" => "required",
        ]);
        //verificar si ya existe el curso
        $verificar = DB::select(" select count(*) as 'total' from curso where nombre='$request->nombre' and id_curso!=$id ");
        foreach ($verificar as $key => $value) {
            if ($value->total > 0) {
                return back()->with("DUPLICADO", "El curso '$request->nombre' ya existe");
            }
        }

        //ahora modificamos
        try {
            $sql = DB::update(" update curso set nombre=?, horas=?, descripcion=?, inicio=?, termino=?, estado=? where id_curso=? ", [
                $request->nombre,
                $request->hora,
                $request->descripcion,
                $request->inicio,
                $request->termino,
                $request->estado,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Curso modificado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar, intente nuevamente");
        }
    }

    public function destroy($id)
    {

        try {
            $buscarModelo = DB::select("select modelo from certificado where id_curso=$id");
            $rutaAn = public_path("modelo_certificados/" . $buscarModelo[0]->modelo);
            unlink("$rutaAn");
        } catch (\Throwable $th) {
            //throw $th;
        }
        try {
            $sql = DB::delete(" delete from curso where id_curso=$id ");
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return redirect()->route("curso.index")->with("CORRECTO", "Curso eliminado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }

    public function exportCurso()
    {
        return Excel::download(new CursoExport, 'cursos.xlsx'); //csv
    }

    public function buscar($id)
    {
        try {
            $sql = DB::select("select * from curso where nombre like '%$id%' or descripcion like '%$id%' limit 10 ");
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if (count($sql) > 0) {
            return response()->json(['dato' => $sql], 200);
        } else {
            return response()->json(['error' => "Mensaje de error"], 500);
        }
    }

    public function ver($id)
    {
        $sql = DB::select(" select * from curso where id_curso=$id ");
        return view("cursos/viewCurso", compact("sql"));
    }
}

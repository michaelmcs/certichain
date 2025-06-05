<?php

namespace App\Http\Controllers;

use App\Exports\ModeloParticipanteExport;
use App\Exports\ParticipanteExport;
use App\Exports\TemarioExport;
use App\Imports\ParticipanteImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ParticipanteController extends Controller
{

    public function index()
    {
        $datos = DB::table('participante')
            ->join('curso', 'participante.id_curso', '=', 'curso.id_curso')
            ->select('participante.id_participante', 'participante.id_curso', 'participante.dni', 'participante.nombre', 'participante.apellido', 'participante.correo', 'participante.codigo', 'participante.participo_como', 'participante.certificado', 'curso.nombre as curso')
            ->orderBy("id_participante", "desc")
            ->paginate(10);

        $curso = DB::select(" select * from curso ");
        return view("participante/listaParticipante")->with("datos", $datos)->with("curso", $curso);
    }


    public function create()
    {
        $curso = DB::select(" select * from curso ");
        return view("participante/registroParticipante")->with("curso", $curso);
    }

    public function store(Request $request)
    {

        return $request;

        $request->validate([
            "curso" => "required",
            "dni" => "required",
            "nombre" => "required",
            "apellido" => "required",
            "codigo" => "required|unique:Participante,codigo",
            "participa" => "required",
            "certificado" => "mimes:pdf",
        ]);


        //verificar si ya existe el curso
        $verificar = DB::select("SELECT
        Count(*) AS total,
        curso.nombre,
        participante.codigo
        FROM
        participante
        INNER JOIN curso ON participante.id_curso = curso.id_curso where participante.dni='$request->dni' and participante.id_curso=$request->curso ");
        foreach ($verificar as $key => $value) {
            if ($value->total >= 1) {
                return back()->with("DUPLICADO", "El participante '$request->nombre $request->apellido' con DNI: '$request->dni' ya está inscrito en el curso '$value->nombre' ");
            }
        }

        if ($request->hasFile("certificado")) {
            $file = $request->file("certificado");
            $nombreFile = "cer_" . $request->curso . "_" . $request->dni . "_" . $request->codigo . "_" . $request->apellido . "_" . $request->nombre . "." . $file->guessExtension();
            $ruta = public_path("certificados/" . $nombreFile);
            copy($file, $ruta);
            try {
                $sql = DB::insert(" insert into participante(id_curso,dni,nombre,apellido,correo,codigo,participo_como,certificado)values(?,?,?,?,?,?,?,?) ", [
                    $request->curso,
                    $request->dni,
                    $request->nombre,
                    $request->apellido,
                    $request->correo,
                    $request->codigo,
                    $request->participa,
                    $nombreFile,

                ]);
            } catch (\Throwable $th) {
                $sql = 0;
            }
        } else {
            //ahora registramos
            try {
                $sql = DB::insert(" insert into participante(id_curso,dni,nombre,apellido,correo,codigo,participo_como)values(?,?,?,?,?,?,?) ", [
                    $request->curso,
                    $request->dni,
                    $request->nombre,
                    $request->apellido,
                    $request->correo,
                    $request->codigo,
                    $request->participa
                ]);
            } catch (\Throwable $th) {
                $sql = 0;
            }
        }



        if ($sql == 1) {
            return back()->with("CORRECTO", "Participante registrado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al registrar, intente nuevamente");
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            "curso" => "required",
            "dni" => "required",
            "nombre" => "required",
            "apellido" => "required",
            "codigo" => "required",
            "participa" => "required",
        ]);
        

        //verificar si ya existe el particicpante en el mismo curso
        $verificar = DB::select(" SELECT
        Count(*) AS total,
        curso.nombre,
        participante.codigo
        FROM
        participante
        INNER JOIN curso ON participante.id_curso = curso.id_curso where participante.dni='$request->dni' and participante.id_curso=$request->curso and id_participante!=$id ");
        foreach ($verificar as $key => $value) {
            if ($value->total > 0) {
                return back()->with("DUPLICADO", "El participante '$request->nombre $request->apellido' con DNI: '$request->dni' ya está inscrito en el curso '$value->nombre' ");
            }
        }


        //verificar si ya existe el codigo
        try {
            $verificarCodigo = DB::select(" SELECT Count(*) AS total,participante.codigo FROM participante where codigo='$request->codigo' and id_participante!=$id");
            if ($verificarCodigo[0]->total >= 1) {
                return back()->with("DUPLICADO", "El codigo '$request->codigo' ya esta en uso");
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        

        //buscamos el nombre de certificado para eliminar
        if ($request->participa != "otro") {
            try {
                $buscar = DB::select(" select certificado from participante where id_participante=$id ");
                $rutaAn = public_path("certificados/" . $buscar[0]->certificado);
                $rutaAn2 = public_path("QR_asistentes/" . "$id.png");
                unlink("$rutaAn");
                unlink("$rutaAn2");
                $sql2 = DB::update(" update participante set certificado=''  where id_participante=$id ");
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        //ahora modificamos
        try {
            $sql = DB::update(" update participante set id_curso=?, dni=?, nombre=?, apellido=?, correo=?, codigo=?,participo_como=?  where id_participante=? ", [
                $request->curso,
                $request->dni,
                $request->nombre,
                $request->apellido,
                $request->correo,
                $request->codigo,
                $request->participa,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }



        if ($sql == 1) {
            return back()->with("CORRECTO", "Participante modificado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar, intente nuevamente");
        }
    }

    public function destroy($id)
    {
        try {
            $sql = DB::delete(" delete from participante where id_participante=$id ");
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return redirect()->route("participante.index")->with("CORRECTO", "Participante eliminado exitosamente");
        } else {
            return redirect()->route("participante.index")->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }


    public function exportParticipante()
    {
        return Excel::download(new ParticipanteExport, 'participantes.xlsx'); //csv
    }

    public function exportModeloParticipante()
    {
        return Excel::download(new ModeloParticipanteExport, 'modelo-formato-participantes.xlsx'); //csv
    }

    public function importParticipante(Request $request)
    {
        // return $request;

        $request->validate([
            "dato" => "required|file|mimes:xlsx,xls,csv",
            "id_curso" => "required"
        ]);

        try {
            $eliminar = $request->reemplazar;
        } catch (\Throwable $th) {
            $eliminar = "";
        }

        if ($eliminar != "") {
            $sql = DB::delete(" delete from participante where id_curso=$request->id_curso ");
        }


        $file = $request->file("dato"); //dato es el name

        Excel::import(new ParticipanteImport($request->id_curso), $file);
        return back()->with("CORRECTO", "Los datos se han cargado exitosamente");
    }


    public function modificarCert(Request $request, $id)
    {
        $request->validate([
            "certificado" => "required|mimes:pdf",
            "curso" => "required",
            "dni" => "required",
            "codigo" => "required",
            "apellido" => "required",
            "nombre" => "required",
        ]);
        $sql = DB::select(" select * from participante where id_participante=$id ");
        foreach ($sql as $key => $value) {
            $dni = $value->dni;
            $curso = $value->id_curso;
        }

        $file = $request->file("certificado");
        //$nombreFile = "cer_" . $dni . "_" . $curso . "." . $file->guessExtension();
        $nombreFile = "cer_" . $request->curso . "_" . $request->dni . "_" . $request->codigo . "_" . $request->apellido . "_" . $request->nombre . "." . $file->guessExtension();
        $ruta = public_path("certificados/" . $nombreFile);
        $res = copy($file, $ruta);

        $actualizar = DB::update(" update participante set certificado='$nombreFile' where id_participante=$id ");

        if ($res) {
            return back()->with("CORRECTO", "Certificado modificado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar, intente nuevamente");
        }
    }

    public function eliminarCert($id)
    {
        try {
            $sql = DB::select(" select certificado from participante where id_participante=$id ");
            $actualizar = DB::update(" update participante set certificado='' where id_participante=$id ");
            foreach ($sql as $key => $value) {
                $certificado = $value->certificado;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $ruta = public_path("certificados/" . $certificado);
        $res = unlink("$ruta");

        if ($res) {
            return back()->with("CORRECTO", "Certificado eliminado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }

    public function crearQR($id_participante)
    {
        $qr = QrCode::format('png')->size(500)->generate(route('welcome') . "/verMiCertificadoQR/$id_participante");
        $file_path = public_path("QR_asistentes/$id_participante.png");
        file_put_contents($file_path, $qr);
        return back()->with("CORRECTO", "QR generado correctamente");
    }


    public function buscar($id)
    {
        try {
            $sql = DB::select(" SELECT
            participante.*,
            curso.nombre as 'nomCurso'
            FROM
            participante
            INNER JOIN curso ON participante.id_curso = curso.id_curso
            where dni like '%$id%' or participante.nombre like '%$id%' or participante.apellido like '%$id%' 
            or CONCAT(participante.nombre, ' ', participante.apellido) like '%$id%' 
            limit 10 ");
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
        $sql = DB::select(" SELECT
        participante.*,
        curso.nombre as 'nomCurso'
        FROM
        participante
        INNER JOIN curso ON participante.id_curso = curso.id_curso
        where id_participante=$id ");
        $curso = DB::select(" select * from curso ");
        return view("participante/viewParticipante", compact("sql"))->with("curso", $curso);
    }

    public function eliminarTodo(){
        try {
            $sql = DB::delete(" delete from participante ");
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql >= 1) {
            return redirect()->route("participante.index")->with("CORRECTO", "Participantes eliminado exitosamente");
        } else {
            return redirect()->route("participante.index")->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }
}

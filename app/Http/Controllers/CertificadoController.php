<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use TCPDF;

class CertificadoController extends Controller
{
public function index()
{
    $datos = DB::table('certificado')
        ->join('curso', 'certificado.id_curso', '=', 'curso.id_curso')
        ->select(
            'certificado.id_certificado',
            'certificado.modelo',
            'curso.nombre',
            'curso.descripcion',
            'curso.id_curso'
        )
        ->paginate(10);

    return view("certificado/listaCertificado", compact('datos'));
}


    public function show($id)
    {
        $datos = DB::select("SELECT * FROM certificado WHERE id_certificado = ?", [$id]);
        return view("certificado/ajustarCertificado", compact('datos'));
    }

    public function guardarPosicion(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'campo' => 'required|string',
            'tipo' => 'required|in:texto,imagen',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'w' => 'nullable|numeric',
            'h' => 'nullable|numeric',
            'f' => 'nullable|numeric',
            'fuente' => 'nullable|string|max:100',
            'forzar' => 'nullable|numeric'
        ]);

        $elemento = DB::table('certificado_elemento')
            ->where('id_certificado', $request->id)
            ->where('nombre', $request->campo)
            ->first();

        $data = [
            'id_certificado' => $request->id,
            'tipo' => $request->tipo,
            'nombre' => $request->campo,
            'x' => round($request->x, 2),
            'y' => round($request->y, 2),
            'ancho' => $request->w !== null ? round($request->w, 2) : null,
            'alto' => $request->h !== null ? round($request->h, 2) : null,
            'tamano_fuente' => $request->f !== null ? round($request->f, 1) : null,
            'fuente' => $request->fuente ?? 'Arial',
            'forzar_ancho' => $request->forzar !== null ? round($request->forzar, 2) : null
        ];

        if ($elemento) {
            DB::table('certificado_elemento')
                ->where('id_elemento', $elemento->id_elemento)
                ->update($data);
        } else {
            DB::table('certificado_elemento')->insert($data);
        }

        return response()->json(['success' => true]);
    }

    public function verPDF($id)
    {
        $certificado = DB::table('certificado')->where('id_certificado', $id)->first();
        if (!$certificado) abort(404);

        $elementos = DB::table('certificado_elemento')
            ->where('id_certificado', $id)
            ->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(0, 0, 0);
        $pdf->Image(public_path("modelo_certificados/{$certificado->modelo}"), 0, 0, 297, 210);

        foreach ($elementos as $el) {
            if ($el->tipo === 'texto') {
                $texto = $el->contenido ?? strtoupper($el->nombre);
                $fuente = $el->fuente ?? 'helvetica';
                $tam = $el->tamano_fuente ?? 12;
                $alto = round($tam * 0.35, 2);
                $ancho = $el->forzar_ancho ?? ($el->ancho ?? mb_strlen($texto) * $tam * 0.6 / 3.78);

                $pdf->SetFont($fuente, 'B', $tam);
                $pdf->SetDrawColor(255, 0, 0);
                $pdf->Rect($el->x, $el->y, $ancho, $alto);
                $pdf->MultiCell($ancho, $alto, $texto, 0, 'C', false, 1, $el->x, $el->y, true);
            } elseif ($el->tipo === 'imagen' && $el->nombre === 'qr') {
                $rutaQR = public_path("qr/prueba.png");
                QrCode::format('png')->size(500)->generate(route("welcome") . "/verMiCertificadoQR/prueba", $rutaQR);
                $pdf->Image($rutaQR, $el->x, $el->y, $el->ancho, $el->alto);
                $pdf->Rect($el->x, $el->y, $el->ancho, $el->alto);
            }
        }

        $pdf->Output("certificado-$id.pdf", 'I');
    }

   public function store(Request $request)
{
    $request->validate([
        'curso' => 'required',
        'modelo' => 'required|image|mimes:jpeg,png,jpg',
    ]);

    // Verificar duplicado
    $existe = DB::table('certificado')->where('id_curso', $request->curso)->exists();
    if ($existe) return back()->with('DUPLICADO', 'El registro ya existe');

    // Guardar archivo
    $file = $request->file('modelo');
    $nombreFile = $request->curso . '.' . $file->guessExtension();
    $file->move(public_path("modelo_certificados"), $nombreFile);

    // Insertar certificado
    $id = DB::table('certificado')->insertGetId([
        'id_curso' => $request->curso,
        'modelo' => $nombreFile
    ]);

    // Insertar elementos por defecto
    $elementos = [
        [
            'tipo' => 'texto',
            'nombre' => 'NOMBRE_COMPLETO',
            'contenido' => 'YAVÉ JEHOVÁ ISRAEL ZEUS INTI AMÓN OSIRIS JAVIER MUÑOZ RODRÍGUEZ',
            'x' => 67.90,
            'y' => 90.20,
            'ancho' => 219.50,
            'alto' => 6.00,
            'fuente' => 'Arial',
            'tamano_fuente' => 17.0,
            'alineacion' => 'center',
        ],
        [
            'tipo' => 'texto',
            'nombre' => 'TIPO_PARTICIPANTE',
            'contenido' => 'PARTICIPANTE',
            'x' => 147.50,
            'y' => 99.90,
            'ancho' => 42.00,
            'alto' => 4.60,
            'forzar_ancho' => 42.00,
            'fuente' => 'Comic Sans MS',
            'tamano_fuente' => 13.0,
            'alineacion' => 'center',
        ],
        [
            'tipo' => 'texto',
            'nombre' => 'CODIGO',
            'contenido' => 'VRA-DGA-C6-230090',
            'x' => 87.70,
            'y' => 187.60,
            'ancho' => 43.50,
            'alto' => 4.20,
            'fuente' => 'Comic Sans MS',
            'tamano_fuente' => 12.0,
            'alineacion' => 'center',
        ],
        [
            'tipo' => 'imagen',
            'nombre' => 'QR',
            'contenido' => '',
            'x' => 229.30,
            'y' => 32.90,
            'ancho' => 41.00,
            'alto' => 40.70,
            'fuente' => 'Arial',
            'tamano_fuente' => 12.0,
            'alineacion' => 'center',
        ],
    ];

    foreach ($elementos as $el) {
        DB::table('certificado_elemento')->insert(array_merge($el, [
            'id_certificado' => $id,
            'fecha_agregado' => now()
        ]));
    }

    return back()->with('CORRECTO', 'Certificado registrado exitosamente');
}





 public function add(Request $request, $id)
{
    $request->validate([
        "modelo" => "required|image|mimes:jpeg,png,jpg",
        "curso" => "required",
    ]);

    // Obtener modelo anterior
    $modeloAnterior = DB::table('certificado')->where('id_certificado', $id)->value('modelo');

    // Eliminar archivo anterior si existe
    if ($modeloAnterior) {
        @unlink(public_path("modelo_certificados/$modeloAnterior"));
    }

    // Guardar nuevo archivo
    $file = $request->file("modelo");
    $nombreFile = $request->curso . '.' . $file->guessExtension();
    $file->move(public_path("modelo_certificados"), $nombreFile);

    // Actualizar modelo del certificado
    DB::update("UPDATE certificado SET modelo = ? WHERE id_certificado = ?", [$nombreFile, $id]);

    // Verificar si ya tiene elementos registrados
    $tieneElementos = DB::table('certificado_elemento')->where('id_certificado', $id)->exists();

    if (!$tieneElementos) {
        $elementos = [
            [
                'tipo' => 'texto',
                'nombre' => 'NOMBRE_COMPLETO',
                'contenido' => 'YAVÉ JEHOVÁ ISRAEL ZEUS INTI AMÓN OSIRIS JAVIER MUÑOZ RODRÍGUEZ',
                'x' => 67.90,
                'y' => 90.20,
                'ancho' => 219.50,
                'alto' => 6.00,
                'fuente' => 'Arial',
                'tamano_fuente' => 17.0,
                'alineacion' => 'center',
            ],
            [
                'tipo' => 'texto',
                'nombre' => 'TIPO_PARTICIPANTE',
                'contenido' => 'PARTICIPANTE',
                'x' => 147.50,
                'y' => 99.90,
                'ancho' => 42.00,
                'alto' => 4.60,
                'forzar_ancho' => 42.00,
                'fuente' => 'Comic Sans MS',
                'tamano_fuente' => 13.0,
                'alineacion' => 'center',
            ],
            [
                'tipo' => 'texto',
                'nombre' => 'CODIGO',
                'contenido' => 'VRA-DGA-C6-230090',
                'x' => 87.70,
                'y' => 187.60,
                'ancho' => 43.50,
                'alto' => 4.20,
                'fuente' => 'Comic Sans MS',
                'tamano_fuente' => 12.0,
                'alineacion' => 'center',
            ],
            [
                'tipo' => 'imagen',
                'nombre' => 'QR',
                'contenido' => '',
                'x' => 229.30,
                'y' => 32.90,
                'ancho' => 41.00,
                'alto' => 40.70,
                'fuente' => 'Arial',
                'tamano_fuente' => 12.0,
                'alineacion' => 'center',
            ],
        ];

        foreach ($elementos as $el) {
            DB::table('certificado_elemento')->insert(array_merge($el, [
                'id_certificado' => $id,
                'fecha_agregado' => now()
            ]));
        }
    }

    return back()->with("CORRECTO", "Certificado actualizado exitosamente");
}


    public function ver($id)
    {
        $sql = DB::select("SELECT certificado.id_certificado, certificado.modelo, curso.nombre, curso.id_curso
                           FROM certificado
                           RIGHT JOIN curso ON certificado.id_curso = curso.id_curso
                           WHERE curso.id_curso = ?", [$id]);
        return view("certificado/viewCertificado", compact("sql"));
    }

    public function delete($id)
    {
        $modelo = DB::table('certificado')->where('id_certificado', $id)->value('modelo');
        if ($modelo) {
            @unlink(public_path("modelo_certificados/$modelo"));
        }

        DB::update("UPDATE certificado SET modelo = '' WHERE id_certificado = ?", [$id]);

        return back()->with("CORRECTO", "El modelo del certificado se eliminó exitosamente");
    }

public function buscar($id)
{
    try {
        $sql = DB::select("
            SELECT curso.id_curso, curso.nombre, curso.descripcion, certificado.id_certificado
            FROM curso
            LEFT JOIN certificado ON curso.id_curso = certificado.id_curso
            WHERE curso.nombre LIKE ? OR curso.descripcion LIKE ?
            ORDER BY curso.id_curso DESC
            LIMIT 10
        ", ["%$id%", "%$id%"]);

        return response()->json(['dato' => $sql]);
    } catch (\Throwable $th) {
        return response()->json(['error' => 'Error al buscar cursos'], 500);
    }
}

}

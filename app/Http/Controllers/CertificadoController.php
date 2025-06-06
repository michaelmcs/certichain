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

    public function registrarEnBlockchain($id)
    {
        $certificado = DB::table('modelo_certificado')->where('id_modelo_certificado', $id)->first();

        if (!$certificado) {
            return back()->with('error', 'Certificado no encontrado');
        }

        $rutaPDF = public_path("modelo_certificados/" . $certificado->archivo);

        if (!file_exists($rutaPDF)) {
            return back()->with('error', 'Archivo PDF no encontrado: ' . $rutaPDF);
        }

        $hash = hash_file('sha256', $rutaPDF);
        $cmd = "ots stamp \"$rutaPDF\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            return back()->with('error', 'Error ejecutando ots. Asegúrate de tener opentimestamps-cli instalado');
        }

        $otsName = $certificado->archivo . '.ots';

        DB::table('modelo_certificado')->where('id_modelo_certificado', $id)->update([
            'hash_certificado' => $hash,
            'archivo_ots' => $otsName,
        ]);

        return back()->with('success', 'Certificado registrado en blockchain correctamente');
    }

    public function verPDF($id)
    {
        $certificado = DB::table('modelo_certificado')->where('id_modelo_certificado', $id)->first();
        if (!$certificado) abort(404);

        $elementos = DB::table('certificado_elemento')
            ->where('id_modelo_certificado', $id)
            ->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(0, 0, 0);
        $pdf->Image(public_path("modelo_certificados/{$certificado->archivo}"), 0, 0, 297, 210);

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

    public function buscar($id)
    {
        try {
            $sql = DB::select("SELECT curso.id_curso, curso.nombre, curso.descripcion, modelo_certificado.id_modelo_certificado FROM curso LEFT JOIN modelo_certificado ON curso.id_curso = modelo_certificado.id_curso WHERE curso.nombre LIKE ? OR curso.descripcion LIKE ? ORDER BY curso.id_curso DESC LIMIT 10", ["%$id%", "%$id%"]);
            return response()->json(['dato' => $sql]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al buscar cursos'], 500);
        }
    }
}

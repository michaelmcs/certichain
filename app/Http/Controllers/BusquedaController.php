<?php

namespace App\Http\Controllers;

use App\Mail\BusquedaMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use TCPDF;

class BusquedaController extends Controller
{
    public function index()
    {
        return view("layouts/formBusqueda");
    }

    public function enviarCodigo($participante, $curso, $codigo)
    {
        $codigoParti = DB::table('participante')
            ->where('id_participante', $participante)
            ->value('cod_verificacion');

        if ($codigo == $codigoParti) {
            return response()->json([
                'mensaje' => 'success',
                "id_participante" => $participante,
                "codigo" => $codigo
            ]);
        } else {
            return response()->json(['mensaje' => 'error']);
        }
    }

    public function verCertificado($participante, $codigo = 0)
    {
        try {
            $data = DB::table('participante')
                ->join('curso', 'participante.id_curso', '=', 'curso.id_curso')
                ->join('certificado', 'certificado.id_curso', '=', 'curso.id_curso')
                ->where('id_participante', $participante)
                ->select('participante.cod_verificacion', 'participante.certificado', 'participante.participo_como', 'certificado.id_certificado')
                ->first();

            return view("certificados/resultadoBusqueda")
                ->with("id_certificado", $data->id_certificado)
                ->with("id_participante", $participante)
                ->with("certPart", $data->certificado)
                ->with("participo_como", $data->participo_como);
        } catch (\Throwable $th) {
            return redirect()->route("welcome")->with("mensaje", "No puedes acceder a los Certificados, sin los permisos necesarios");
        }
    }

    public function buscar(Request $request)
    {
        $datos = DB::select("SELECT participante.*, curso.nombre as curso
                             FROM participante
                             INNER JOIN curso ON participante.id_curso = curso.id_curso
                             WHERE participante.dni = ? OR participante.codigo = ?
                             ORDER BY id_participante DESC", [$request->dni, $request->dni]);

        $count = count($datos);
        $mensaje = $count
            ? "<div class='alert alert-success'>¡Se han encontrado $count certificados!</div>"
            : "<div class='alert alert-danger'>No se han encontrado registros. Consulte con el Administrador</div>";

        session()->flash('MENSAJE', new HtmlString($mensaje));
        return view("certificados/busqueda")->with("datosCert", $datos);
    }

    public function enviar($id)
    {
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $correo = DB::table('participante')->where('id_participante', $id)->value('correo');

        DB::update("UPDATE participante SET cod_verificacion = ? WHERE id_participante = ?", [$codigo, $id]);
        $correoMailable = new BusquedaMailable($codigo);

        try {
            Mail::to($correo)->send($correoMailable);
            return response()->json(['mensaje' => 'Correo enviado correctamente']);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Falló el envío del correo electrónico']);
        }
    }

    public function verPDF($id, $participante)
    {
        $certificado = DB::table('certificado')->where('id_certificado', $id)->first();
        $participanteData = DB::table('participante')->where('id_participante', $participante)->first();
        $cursoData = DB::table('curso')->where('id_curso', $participanteData->id_curso)->first();
        $elementos = DB::table('certificado_elemento')->where('id_certificado', $id)->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(0, 0, 0);
        $pdf->Image(public_path("modelo_certificados/{$certificado->modelo}"), 0, 0, 297, 210);

        foreach ($elementos as $el) {
            $x = $el->x;
            $y = $el->y;
            $w = $el->forzar_ancho ?? $el->ancho ?? 60;
            $h = $el->alto ?? 10;
            $f = $el->tamano_fuente ?? 12;

            $fuente = strtolower($el->fuente ?? 'helvetica');
            $fuentesTCPDF = [
                'arial' => 'helvetica',
                'times new roman' => 'times',
                'courier' => 'courier',
                'georgia' => 'dejavuserif',
                'comic sans ms' => 'dejavusans',
                'verdana' => 'dejavusans',
                'aegean' => 'dejavusans',
                'symphony' => 'dejavusans'
            ];
            $fuente = $fuentesTCPDF[$fuente] ?? 'helvetica';

            $pdf->SetFont($fuente, 'B', $f);
            $pdf->SetDrawColor(255, 0, 0);
            $pdf->SetLineWidth(0.25);

            if ($el->tipo === 'imagen') {
                if (strtolower($el->nombre) === 'qr') {
                    $qrRuta = public_path("qr/{$participante}.png");
                    $urlQR = route("welcome") . "/verMiCertificadoQR/{$participante}";
                    QrCode::format('png')->size(500)->generate($urlQR, $qrRuta);
                    $pdf->Image($qrRuta, $x, $y, $w, $h);
                } else if ($el->contenido) {
                    $ruta = public_path("certificados/{$el->contenido}");
                    if (file_exists($ruta)) {
                        $pdf->Image($ruta, $x, $y, $w, $h);
                    }
                }
                continue;
            }

            switch (strtolower(trim($el->nombre))) {
                case 'nombre':
                case 'nombre_completo':
                    $texto = strtoupper(trim($participanteData->nombre . ' ' . $participanteData->apellido));
                    break;
                case 'codigo':
                    $texto = $participanteData->codigo;
                    break;
                case 'dni':
                    $texto = $participanteData->dni;
                    break;
                case 'correo':
                    $texto = $participanteData->correo;
                    break;
                case 'rol':
                case 'cargo':
                case 'tipo':
                case 'tipo_participante':
                    $texto = strtoupper($participanteData->participo_como);
                    break;
                case 'curso':
                    $texto = strtoupper($cursoData->nombre ?? '');
                    break;
                default:
                    $texto = $el->contenido ?? strtoupper($el->nombre);
            }

            $pdf->MultiCell($w, $h, $texto, 0, 'C', false, 1, $x, $y, true);
        }

        $nombreArchivo = "certificado_{$participanteData->dni}_{$participanteData->codigo}.pdf";
        $pdf->Output($nombreArchivo, 'I');
    }

    public function verPDFQR($id)
    {
        $datos = DB::select("SELECT participante.*, curso.horas, certificado.id_certificado
                             FROM participante
                             INNER JOIN curso ON participante.id_curso = curso.id_curso
                             INNER JOIN certificado ON certificado.id_curso = curso.id_curso
                             WHERE id_participante = ?", [$id]);

        if (!$datos || count($datos) === 0) {
            return redirect()->route("welcome")->with("aviso", "El certificado NO existe");
        }

        $modo = $datos[0]->certificado ? "tiene" : "notiene";
        return view("certificados/vistaPorQR", compact("datos"))->with("modo", $modo);
    }
}

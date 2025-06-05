<?php

namespace App\Http\Controllers;

use App\Mail\BusquedaMailable;
use App\Mail\PasswordMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class RecuperarClaveController extends Controller
{
    public function index()
    {
        return view("auth/recuperarPassword");
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            "dni" => "required",
            "correo" => "required"
        ]);

        try {
            $validar = DB::select("select count(*) as 'total' from usuario where dni='$request->dni' and correo='$request->correo'");
            foreach ($validar as $key => $value) {
                $total = $value->total;
            }
        } catch (\Throwable $th) {
            $total = 0;
        }
        if ($total >= 1) {
            $code = Str::random(6);
            $actualizar = DB::update('update usuario set password=? where dni=? and correo=?', [
                md5($code),
                $request->dni,
                $request->correo
            ]);

            $correo = new PasswordMailable($code); //enviamos estos datos al MAILABLE
            try {
                Mail::to($request->correo)->send($correo);
                return back()->with("CORRECTO", "Te hemos enviado un codigo a tu correo, Por favor revísalo");
            } catch (\Throwable $th) {
                return back()->with("INCORRECTO", "No hemos podido enviarte un correo, Por favor consulte con el administrador");
            }
        } else {
            return back()->with("INCORRECTO", "No hemos encontrado registros con esos datos");
        }
    }
}

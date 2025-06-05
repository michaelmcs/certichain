<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MiPerfilController extends Controller
{

    public function miPasswordIndex()
    {
        return view("cambiarClave");
    }

    public function miPasswordEditar(Request $request)
    {
        $request->validate([
            "claveActual" => "required",
            "claveNuevo" => "required",
        ]);

        $id = Auth::user()->id_usuario;
        $verClaveAn = DB::select(" select password from usuario where id_usuario=$id ");
        $pass = md5($request->claveNuevo);

        if ($verClaveAn[0]->password != md5($request->claveActual)) {
            return back()->with("INCORRECTO", "La contraseña actual es INCORRECTA");
        }
        try {
            $sql = DB::update(" update usuario set password=? where id_usuario=? ", [
                $pass,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Contraseña modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function miPerfilIndex()
    {
        $id = Auth::user()->id_usuario;
        $sql = DB::select(" select * from usuario where id_usuario=$id ");
        return view("miPerfil", compact("sql"));
    }

    public function miPerfilEditar(Request $request)
    {

        $request->validate([
            "id" => "required",
            "dni" => "required",
            "nombre" => "required",
            "usuario" => "required",
            "correo" => "required",
        ]);

        try {
            $verificar = DB::select(" select count(*) as 'total' from usuario where usuario='$request->usuario' and id_usuario!=$request->id ");
            foreach ($verificar as $key => $value) {
                $total = $value->total;
            }
        } catch (\Throwable $th) {
            $verificar = "";
        }

        if ($total >= 1) {
            return back()->with("CORRECTO", "Ingrese otro nombre en el campo usuario");
        } else {
            try {
                $sql = DB::update(" update usuario set usuario=?, dni=?, nombres=?, telefono=?, correo=? where id_usuario=? ", [
                    $request->usuario,
                    $request->dni,
                    $request->nombre,
                    $request->telefono,
                    $request->correo,
                    $request->id
                ]);
                if ($sql == 0) {
                    $sql = 1;
                }
            } catch (\Throwable $th) {
                $sql = 0;
            }
            if ($sql == 1) {
                return back()->with("CORRECTO", "Datos modificado correctamente");
            } else {
                return back()->with("INCORRECTO", "Error al modificar");
            }
        }
    }

    public function perfilUpdatePerfil(Request $request)
    {

        $request->validate([
            "id" => "required",
            "foto" => "required|mimes:jpeg,png,jpg",
        ]);

        $id = $request->id;
        //eliminamos la img anterior
        try {
            $buscarNombre = DB::select(" select foto from usuario where id_usuario=$id ");
            $nomFoto = $buscarNombre[0]->foto;
            $rutaAn = public_path("foto/usuario/" . $nomFoto);
            unlink("$rutaAn");
        } catch (\Throwable $th) {
        }


        //guardando la foto en servidor
        try {
            $file = $request->file("foto");
            $nombreFile = $id . "." . $file->guessExtension();
            $ruta = public_path("foto/usuario/" . $nombreFile);
            copy($file, $ruta);
        } catch (\Throwable $th) {
            $nombreFile = "";
        }

        try {
            $sql = DB::update(" update usuario set foto=? where id_usuario=? ", [
                $nombreFile,
                $request->id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Datos modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function perfilDeletePerfil($id)
    {


        //eliminamos la img anterior
        try {
            $buscarNombre = DB::select(" select foto from usuario where id_usuario=$id ");
            $nomFoto = $buscarNombre[0]->foto;
            $rutaAn = public_path("foto/usuario/" . $nomFoto);
            unlink("$rutaAn");
        } catch (\Throwable $th) {
        }

        try {
            $sql = DB::update(" update usuario set foto='' where id_usuario=? ", [
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }



        if ($sql == 1) {
            return back()->with("CORRECTO", "Datos modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }
}

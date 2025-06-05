<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $datos = DB::select(" select * from usuario ");
        return view("usuario/listaUsuario")->with("datos", $datos);
    }


    public function create()
    {
        return view("usuario/registroUsuario");
    }

    public function store(Request $request)
    {
        $request->validate([
            "usuario" => "required|unique:usuario,usuario",
            "password" => "required",
            "dni" => "required",
            "nombre" => "required",
            "correo" => "required",
            "foto" => "mimes:jpeg,png,jpg"
        ]);


        // //verificar si ya existe el usuario
        // $verificar = DB::select("SELECT
        // Count(*) AS total,
        // usuario.usuario
        // FROM
        // usuario where usuario='$request->usuario' ");
        // foreach ($verificar as $key => $value) {
        //     if ($value->total >= 1) {
        //         return back()->with("DUPLICADO", "El usuario '$request->usuario' ya existe");
        //     }
        // }


        try {
            $sql = DB::table('usuario')->insertGetId([
                'usuario' => $request->usuario,
                'password' => md5($request->password),
                'dni' => $request->dni,
                'nombres' => $request->nombre,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        //guardando la foto en servidor
        try {
            $file = $request->file("foto");
            $nombreFile = $sql . "." . $file->guessExtension();
            $ruta = public_path("foto/usuario/" . $nombreFile);
            copy($file, $ruta);
        } catch (\Throwable $th) {
            $nombreFile = "";
        }

        //actualizamos en campo foto con la url de la foto
        try {
            $actualizar = DB::update(" update usuario set foto = '$nombreFile' where id_usuario=$sql ");
            if ($actualizar == 0) {
                $actualizar = 1;
            }
        } catch (\Throwable $th) {
            $actualizar = 0;
        }


        if ($sql >= 1 and $actualizar == 1) {
            return back()->with("CORRECTO", "Usuario registrado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al registrar");
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            "usuario" => "required",
            "dni" => "required",
            "nombre" => "required",
            "correo" => "required",
        ]);
        //verificar si ya existe el usuario
        $verificar = DB::select(" SELECT
        Count(*) AS total,
        usuario.usuario
        FROM
        usuario where usuario='$request->usuario' and id_usuario!=$id ");
        foreach ($verificar as $key => $value) {
            if ($value->total > 0) {
                return back()->with("DUPLICADO", "El usuario '$request->usuario' ya existe");
            }
        }

        //ahora modificamos
        try {
            $sql = DB::update(" update usuario set usuario=?, dni=?, nombres=?, telefono=?, correo=?  where id_usuario=? ", [
                $request->usuario,
                $request->dni,
                $request->nombre,
                $request->telefono,
                $request->correo,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Usuario modificado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar, intente nuevamente");
        }
    }

    public function destroy($id)
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
            $sql = DB::delete(" delete from usuario where id_usuario=$id ");
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Usuario eliminado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }


    public function modificarFoto(Request $request, $id)
    {
        $request->validate([
            "foto" => "required|mimes:jpeg,png,jpg"
        ]);

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


        //actualizamos en campo foto con la url de la foto
        try {
            $actualizar = DB::update(" update usuario set foto = '$nombreFile' where id_usuario=$id ");
            if ($actualizar == 0) {
                $actualizar = 1;
            }
        } catch (\Throwable $th) {
            $actualizar = 0;
        }


        if ($actualizar == 1) {
            return back()->with("CORRECTO", "Usuario modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function eliminarFoto($id)
    {
        $traerRuta = DB::select("select foto from usuario where id_usuario=$id");
        $nombre = $traerRuta[0]->foto;

        $rutaAn = public_path("foto/usuario/" . $nombre);
        try {
            $eliminar = unlink("$rutaAn");
        } catch (\Throwable $th) {
        }

        try {
            $actualizar = DB::update("update usuario set foto='' where id_usuario=$id ");
        } catch (\Throwable $th) {
            $actualizar = 0;
        }

        if ($actualizar == 1) {
            return back()->with("CORRECTO", "Foto eliminado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar");
        }
    }
}

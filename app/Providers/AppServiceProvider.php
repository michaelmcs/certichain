<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ✅ Forzar HTTPS en todas las rutas, ya que Nginx ahora lo maneja bien
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        

        $datos = DB::select("select * from empresa");
        $curso = DB::select("select count(*) as 'total' from curso");
        $cursoProx = DB::select("select * from curso where estado='proximamente'");
        $cursoEncu = DB::select("select * from curso where estado='encurso'");
        $cursoFina = DB::select("select * from curso where estado='finalizado'");
        $participante = DB::select("select count(*) as 'total' from participante");
        $usuario = DB::select("select count(*) as 'total' from usuario");

        View::share('datos', $datos);
        View::share('curso', $curso);
        View::share('participante', $participante);
        View::share('usuario', $usuario);
        View::share('cursoProx', $cursoProx);
        View::share('cursoEncu', $cursoEncu);
        View::share('cursoFina', $cursoFina);
    }
}

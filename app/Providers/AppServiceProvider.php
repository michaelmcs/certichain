<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Solo forzar HTTPS en producción
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Solo ejecutar estas consultas si las tablas existen (evita errores en migraciones, testing, etc.)
        if (
            Schema::hasTable('empresa') &&
            Schema::hasTable('curso') &&
            Schema::hasTable('participante') &&
            Schema::hasTable('usuario')
        ) {
            $datos = DB::select("SELECT * FROM empresa");
            $curso = DB::select("SELECT COUNT(*) AS total FROM curso");
            $cursoProx = DB::select("SELECT * FROM curso WHERE estado = 'proximamente'");
            $cursoEncu = DB::select("SELECT * FROM curso WHERE estado = 'encurso'");
            $cursoFina = DB::select("SELECT * FROM curso WHERE estado = 'finalizado'");
            $participante = DB::select("SELECT COUNT(*) AS total FROM participante");
            $usuario = DB::select("SELECT COUNT(*) AS total FROM usuario");

            // Compartir variables con todas las vistas
            View::share('datos', $datos);
            View::share('curso', $curso);
            View::share('participante', $participante);
            View::share('usuario', $usuario);
            View::share('cursoProx', $cursoProx);
            View::share('cursoEncu', $cursoEncu);
            View::share('cursoFina', $cursoFina);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificadoElementoSeeder extends Seeder
{
    public function run()
    {
        $certificados = DB::table('certificado')->get();

        foreach ($certificados as $cert) {
            $elementos = [
                [
                    'nombre' => 'nombre_completo',
                    'tipo' => 'texto',
                    'x' => 110,
                    'y' => 70,
                    'ancho' => 80,
                    'alto' => 15,
                    'tamano_fuente' => 18
                ],
                [
                    'nombre' => 'asistio_como',
                    'tipo' => 'texto',
                    'x' => 35,
                    'y' => 90,
                    'ancho' => 230,
                    'alto' => 25,
                    'tamano_fuente' => 12
                ],
                [
                    'nombre' => 'codigo',
                    'tipo' => 'texto',
                    'x' => 10,
                    'y' => 195,
                    'ancho' => 60,
                    'alto' => 10,
                    'tamano_fuente' => 10
                ],
                [
                    'nombre' => 'qr',
                    'tipo' => 'imagen',
                    'x' => 250,
                    'y' => 160,
                    'ancho' => 35,
                    'alto' => 35,
                    'tamano_fuente' => null
                ]
            ];

            foreach ($elementos as $el) {
                DB::table('certificado_elemento')->insert([
                    'id_certificado' => $cert->id_certificado,
                    'nombre' => $el['nombre'],
                    'tipo' => $el['tipo'],
                    'x' => $el['x'],
                    'y' => $el['y'],
                    'ancho' => $el['ancho'],
                    'alto' => $el['alto'],
                    'tamano_fuente' => $el['tamano_fuente'],
                    'fecha_agregado' => now()
                ]);
            }
        }
    }
}

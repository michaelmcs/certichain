<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TimestampModeloCertificado extends Command
{
    protected $signature = 'cert:blockchain {id}';
    protected $description = 'Genera hash y archivo OTS para un modelo_certificado';

    public function handle()
    {
        $id = $this->argument('id');
        $modelo = DB::table('modelo_certificado')->where('id_modelo_certificado', $id)->first();

        if (!$modelo) {
            $this->error('Modelo no encontrado');
            return 1;
        }

        $ruta = storage_path('app/' . $modelo->archivo); // asegúrate de guardar ahí
        if (!file_exists($ruta)) {
            $this->error("Archivo no encontrado: $ruta");
            return 1;
        }

        // 1. Calcular hash
        $hash = hash_file('sha256', $ruta);

        // 2. Generar .ots
        $otsPath = $ruta . '.ots';
        $cmd = "ots stamp \"$ruta\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            $this->error('Error generando OTS');
            return 1;
        }

        // 3. Guardar en BD
        DB::table('modelo_certificado')->where('id_modelo_certificado', $id)->update([
            'hash_certificado' => $hash,
            'archivo_ots' => basename($otsPath),
        ]);

        $this->info("Certificado timestamped correctamente.");
        return 0;
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('modelo_certificado', function (Blueprint $table) {
            $table->id('id_modelo_certificado');
            $table->string('nombre', 100);
            $table->string('archivo', 255);

            // Campos para blockchain (si firmas la plantilla)
            $table->string('hash_certificado', 100)->nullable();    // Hash del archivo base
            $table->string('blockchain_tx', 255)->nullable();        // Tx hash si se ancla en blockchain
            $table->string('archivo_ots', 255)->nullable();          // Ruta del .ots (OpenTimestamps)

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('modelo_certificado');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('certificado_elemento', function (Blueprint $table) {
            $table->id('id_elemento');
            $table->unsignedBigInteger('id_modelo_certificado');
            $table->enum('tipo', ['texto', 'imagen']);
            $table->string('nombre', 100)->comment('Nombre identificador del campo: ej. nombre, codigo, qr');
            $table->text('contenido')->nullable()->comment('Contenido fijo si aplica, como PARTICIPANTE o ruta img');
            $table->decimal('x', 6, 2);
            $table->decimal('y', 6, 2);
            $table->decimal('ancho', 6, 2)->nullable();
            $table->decimal('alto', 6, 2)->nullable();
            $table->decimal('forzar_ancho', 6, 2)->nullable();
            $table->string('fuente', 100)->default('helvetica');
            $table->decimal('tamano_fuente', 4, 1)->nullable();
            $table->enum('alineacion', ['left', 'center', 'right'])->default('center');
            $table->dateTime('fecha_agregado')->nullable()->useCurrent();

            $table->foreign('id_modelo_certificado')
                  ->references('id_modelo_certificado')
                  ->on('modelo_certificado')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('certificado_elemento');
    }
};

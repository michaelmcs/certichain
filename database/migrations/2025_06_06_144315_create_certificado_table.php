<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificado', function (Blueprint $table) {
            $table->id('id_certificado');
            $table->unsignedBigInteger('id_curso');
            $table->string('modelo', 255)->nullable()->default('');

            // Posiciones y estilos del otorgante
            $table->string('otorX', 255)->nullable();
            $table->string('otorY', 255)->nullable();
            $table->string('otorL', 255)->nullable();
            $table->string('otorA', 255)->nullable();
            $table->string('otorF', 255)->nullable();
            $table->string('otorFont', 50)->nullable()->default('helvetica');

            // Posiciones y estilos del asistente
            $table->string('asisX', 255)->nullable();
            $table->string('asisY', 255)->nullable();
            $table->string('asisL', 255)->nullable();
            $table->string('asisA', 255)->nullable();
            $table->string('asisF', 255)->nullable();

            // Posiciones y estilos del código
            $table->string('codiX', 255)->nullable();
            $table->string('codiY', 255)->nullable();
            $table->string('codiL', 255)->nullable();
            $table->string('codiA', 255)->nullable();
            $table->string('codiF', 255)->nullable();

            // Posiciones y estilos del QR
            $table->string('qrX', 255)->nullable();
            $table->string('qrY', 255)->nullable();
            $table->string('qrL', 255)->nullable();
            $table->string('qrA', 255)->nullable();
            $table->string('qrF', 255)->nullable();

            $table->dateTime('fecha_agregado')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('fecha_editado')->nullable()->useCurrentOnUpdate();

            // Relaciones
            $table->foreign('id_curso')->references('id_curso')->on('curso')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificado');
    }
};

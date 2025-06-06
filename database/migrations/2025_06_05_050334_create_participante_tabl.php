<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('participante', function (Blueprint $table) {
            $table->id('id_participante');
            $table->unsignedBigInteger('id_curso')->nullable();
            $table->string('dni', 255)->nullable();
            $table->string('nombre', 255)->nullable();
            $table->string('apellido', 255)->default('');
            $table->string('correo', 255)->nullable();
            $table->string('codigo', 255)->nullable();
            $table->string('participo_como', 255)->nullable();
            $table->string('cod_verificacion', 255)->nullable();
            $table->string('certificado', 255)->nullable();
            $table->unsignedInteger('programa_id')->nullable();
            $table->unsignedInteger('tipo_id')->nullable();

            $table->foreign('id_curso')->references('id_curso')->on('curso')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('participante');
    }
};

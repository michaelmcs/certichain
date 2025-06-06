<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('empresa', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->string('nombre', 255)->nullable();
            $table->string('ubicacion', 255)->nullable();
            $table->string('telefono', 20)->default('');
            $table->string('correo', 255)->nullable();
            $table->string('foto', 255)->default('');
            $table->string('fondo', 255)->default('');
        });
    }

    public function down(): void {
        Schema::dropIfExists('empresa');
    }
};

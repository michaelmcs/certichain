<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('usuario', 255);
            $table->string('password', 255);
            $table->string('dni', 255)->nullable();
            $table->string('nombres', 255)->nullable();
            $table->string('telefono', 255)->nullable();
            $table->string('correo', 255)->nullable();
            $table->string('foto', 255)->default('');
        });
    }

    public function down(): void {
        Schema::dropIfExists('usuario');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('curso', function (Blueprint $table) {
            $table->id('id_curso');
            $table->string('nombre', 255);
            $table->string('horas', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->date('inicio')->nullable();
            $table->date('termino')->nullable();
            $table->string('estado', 255)->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('curso');
    }
};

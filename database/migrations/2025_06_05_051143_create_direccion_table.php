<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('direccion', function (Blueprint $table) {
            $table->id('id_director');
            $table->string('dni', 255)->nullable();
            $table->string('grado_nombres', 255)->nullable();
            $table->string('cargo', 255)->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('direccion');
    }
};

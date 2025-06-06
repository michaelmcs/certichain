<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('temario', function (Blueprint $table) {
            $table->id('id_temario');
            $table->unsignedBigInteger('id_curso');
            $table->string('tema', 255)->nullable();

            $table->foreign('id_curso')->references('id_curso')->on('curso')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('temario');
    }
};

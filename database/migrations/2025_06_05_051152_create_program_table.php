<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('program', function (Blueprint $table) {
            $table->id('prog_id');
            $table->string('id_facu', 7)->nullable();
            $table->string('programa', 51)->nullable();
            $table->string('facultad', 50)->nullable();
            $table->string('id_escu', 7)->nullable();
            $table->string('escuela', 36)->nullable();
            $table->string('status', 6)->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('program');
    }
};

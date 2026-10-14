<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora_inicio')->unique(); // Restricción única para evitar bloques duplicados
            $table->dateTime('fecha_hora_fin');
            $table->foreignId('socio_id')->nullable()->constrained('socios')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
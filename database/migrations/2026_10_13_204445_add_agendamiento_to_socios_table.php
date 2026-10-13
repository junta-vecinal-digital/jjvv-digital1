<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->dateTime('fecha_cita_firma')->nullable()->after('estado');
            $table->text('observaciones_cita')->nullable()->after('fecha_cita_firma');
        });
    }

    public function down(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->dropColumn(['fecha_cita_firma', 'observaciones_cita']);
        });
    }
};
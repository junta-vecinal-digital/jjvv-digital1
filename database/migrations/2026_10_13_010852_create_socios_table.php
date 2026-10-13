<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('rut')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->date('fecha_nacimiento');
            $table->string('direccion');
            $table->string('telefono');
            $table->integer('meses_residencia')->default(6);
            
            // Archivos adjuntos
            $table->string('ruta_cedula')->nullable();
            $table->string('ruta_comprobante_domicilio')->nullable();

            // Cumplimiento Legal RO-01 (Firma presencial en libro de registro)
            $table->enum('estado', ['pendiente_firma', 'activo', 'rechazado', 'inactivo'])->default('pendiente_firma');
            $table->timestamp('fecha_firma_libro')->nullable();
            $table->foreignId('registrado_por_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
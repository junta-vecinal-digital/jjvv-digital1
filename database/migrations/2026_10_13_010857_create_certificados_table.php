<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique(); // Ej: CR-2026-00001
            $table->uuid('codigo_verificacion')->unique(); // Para validar mediante QR o portal público
            $table->enum('tipo', ['residencia', 'socio', 'agrupacion', 'otro']);
            
            // Datos del solicitante
            $table->foreignId('socio_id')->nullable()->constrained('socios')->onDelete('set null');
            $table->string('nombre_solicitante');
            $table->string('rut_solicitante');
            $table->string('direccion_solicitante');
            $table->string('presentado_a'); // Institución a la que va dirigido

            // Datos de la emisión
            $table->foreignId('emitido_por_user_id')->constrained('users');
            $table->integer('monto_aporte')->default(1000); // $1.000 voluntario
            $table->enum('estado', ['solicitado', 'aprobado', 'entregado', 'rechazado'])->default('solicitado');
            
            $table->timestamp('fecha_emision')->nullable();
            $table->timestamp('fecha_vencimiento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
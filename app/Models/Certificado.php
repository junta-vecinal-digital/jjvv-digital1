<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    protected $fillable = [
        'folio',
        'codigo_verificacion',
        'tipo',
        'socio_id',
        'nombre_solicitante',
        'rut_solicitante',
        'direccion_solicitante',
        'presentado_a',
        'emitido_por_user_id',
        'monto_aporte',
        'estado',
        'fecha_emision',
        'fecha_vencimiento',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'fecha_vencimiento' => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class);
    }

    public function emitidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emitido_por_user_id');
    }
}
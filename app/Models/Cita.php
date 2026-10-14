<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'socio_id',
    ];

    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioAtencion extends Model
{
    protected $table = 'horarios_atencion';

    protected $fillable = [
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'cupos_maximos',
        'activo',
    ];

    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
        'activo' => 'boolean',
    ];

    // Contar cuántas citas han sido tomadas en este bloque
    public function getCuposReservadosAttribute(): int
    {
        return Socio::whereBetween('fecha_cita_firma', [$this->fecha_hora_inicio, $this->fecha_hora_fin])
            ->count();
    }

    public function getCuposDisponiblesAttribute(): int
    {
        return max(0, $this->cupos_maximos - $this->cupos_reservados);
    }
}
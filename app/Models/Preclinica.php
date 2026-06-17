<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Preclinica extends Model
{
    protected $table = 'preclinicas';

    protected $fillable = [
        'cita_id', 'paciente_id', 'presion_sistolica', 'presion_diastolica',
        'pulso', 'temperatura', 'peso', 'talla', 'notas_iniciales', 'registrado_por_id',
    ];

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por_id');
    }
}

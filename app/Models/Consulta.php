<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consulta extends Model
{
    protected $fillable = [
        'cita_id', 'preclinica_id', 'paciente_id', 'medico_id', 'sintomas',
        'duracion_sintomas', 'presion_sistolica', 'presion_diastolica', 'pulso',
        'temperatura', 'peso', 'talla', 'notas_medicas', 'tratamiento_prescrito', 'firma_digital',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'medico_id');
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }

    public function preclinica(): BelongsTo
    {
        return $this->belongsTo(Preclinica::class);
    }

    public function diagnosticos(): HasMany
    {
        return $this->hasMany(Diagnostico::class);
    }
}

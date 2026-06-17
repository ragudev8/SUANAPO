<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cita extends Model
{
    protected $fillable = [
        'libro_visitas_id', 'paciente_id', 'medico_id', 'tipo_consulta', 'especialidad_id',
        'fecha_hora', 'duracion_estimada', 'estado', 'completada', 'fecha_completado',
    ];

    protected function casts(): array
    {
        return ['fecha_hora' => 'datetime', 'fecha_completado' => 'datetime', 'completada' => 'boolean'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'medico_id');
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function libroVisita(): BelongsTo
    {
        return $this->belongsTo(LibroVisita::class, 'libro_visitas_id');
    }

    public function preclinica(): HasOne
    {
        return $this->hasOne(Preclinica::class);
    }

    public function consulta(): HasOne
    {
        return $this->hasOne(Consulta::class);
    }
}

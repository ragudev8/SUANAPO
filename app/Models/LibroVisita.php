<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LibroVisita extends Model
{
    protected $table = 'libro_visitas';

    protected $fillable = ['paciente_id', 'fecha_visita', 'numero_orden', 'hora_llegada', 'estado', 'registrado_por_id'];

    protected function casts(): array
    {
        return ['fecha_visita' => 'date'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function cita(): HasOne
    {
        return $this->hasOne(Cita::class, 'libro_visitas_id');
    }
}

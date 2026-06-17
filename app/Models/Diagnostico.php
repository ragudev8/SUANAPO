<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnostico extends Model
{
    protected $fillable = ['consulta_id', 'paciente_id', 'descripcion', 'evolucion', 'resuelto', 'fecha_resolucion'];

    protected function casts(): array
    {
        return ['resuelto' => 'boolean', 'fecha_resolucion' => 'datetime'];
    }

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class);
    }
}

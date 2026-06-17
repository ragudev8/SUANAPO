<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleReceta extends Model
{
    protected $table = 'detalles_receta';

    protected $fillable = [
        'receta_id', 'medicamento_id', 'dosis', 'frecuencia', 'cantidad_dias',
        'cantidad_medicamento', 'dispensado', 'fecha_dispensado', 'dispensado_por_id',
    ];

    protected function casts(): array
    {
        return ['dispensado' => 'boolean', 'fecha_dispensado' => 'datetime'];
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class);
    }
}

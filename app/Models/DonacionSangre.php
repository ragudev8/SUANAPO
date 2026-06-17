<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonacionSangre extends Model
{
    protected $table = 'donaciones_sangre';

    protected $fillable = [
        'paciente_donante_id', 'tipo_sangre', 'cantidad_unidades',
        'fecha_donacion', 'estado_salud', 'notas_salud', 'registrado_por_id',
    ];

    protected function casts(): array
    {
        return ['fecha_donacion' => 'date'];
    }

    public function pacienteDonante(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_donante_id');
    }
}

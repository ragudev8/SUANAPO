<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $fillable = [
        'nombre', 'presentacion', 'dosis', 'cantidad_stock', 'cantidad_minima',
        'fecha_vencimiento', 'lote', 'precio_costo', 'activo',
    ];

    protected function casts(): array
    {
        return ['fecha_vencimiento' => 'date', 'activo' => 'boolean'];
    }
}

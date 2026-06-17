<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioSangre extends Model
{
    protected $table = 'inventario_sangre';
    protected $primaryKey = 'tipo_sangre';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = ['tipo_sangre', 'cantidad_disponible', 'ultima_actualizacion'];
}

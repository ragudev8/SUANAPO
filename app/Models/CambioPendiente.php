<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CambioPendiente extends Model
{
    protected $table = 'cambios_pendientes';

    protected $fillable = ['usuario_id', 'tabla', 'operacion', 'payload', 'estado', 'mensaje_error'];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }
}

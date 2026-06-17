<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retroalimentacion extends Model
{
    protected $table = 'retroalimentaciones';

    protected $fillable = [
        'usuario_id',
        'modulo',
        'tipo',
        'prioridad',
        'asunto',
        'mensaje',
        'estado',
        'respuesta_admin',
        'revisado_por_id',
        'revisado_en',
    ];

    protected function casts(): array
    {
        return [
            'revisado_en' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'revisado_por_id');
    }
}

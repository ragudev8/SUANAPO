<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAuditoria extends Model
{
    protected $table = 'logs_auditoria';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id', 'accion', 'tabla_accedida', 'registro_id', 'cambios_json', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return ['cambios_json' => 'array', 'created_at' => 'datetime'];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}

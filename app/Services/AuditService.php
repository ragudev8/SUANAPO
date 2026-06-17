<?php

namespace App\Services;

use App\Models\LogAuditoria;

class AuditService
{
    public function registrar(?int $usuarioId, string $accion, ?string $tabla = null, ?int $registroId = null, ?array $cambios = null): void
    {
        LogAuditoria::create([
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'tabla_accedida' => $tabla,
            'registro_id' => $registroId,
            'cambios_json' => $cambios,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}

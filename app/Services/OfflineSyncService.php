<?php

namespace App\Services;

use App\Models\CambioPendiente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class OfflineSyncService
{
    public function ejecutar(string $tabla, string $operacion, array $payload, callable $callback): mixed
    {
        try {
            DB::connection()->getPdo();

            return DB::transaction($callback);
        } catch (Throwable $exception) {
            CambioPendiente::create([
                'usuario_id' => Auth::id(),
                'tabla' => $tabla,
                'operacion' => $operacion,
                'payload' => $payload,
                'estado' => 'pendiente',
                'mensaje_error' => $exception->getMessage(),
            ]);

            return null;
        }
    }
}

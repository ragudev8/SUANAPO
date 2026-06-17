<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receta extends Model
{
    protected $fillable = [
        'consulta_id', 'paciente_id', 'medico_id', 'folio_unico', 'codigo_qr',
        'fecha_emision', 'fecha_vencimiento', 'estado', 'firma_digital', 'notas',
    ];

    protected function casts(): array
    {
        return ['fecha_emision' => 'date', 'fecha_vencimiento' => 'date'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'medico_id');
    }

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleReceta::class);
    }
}

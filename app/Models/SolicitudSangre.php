<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudSangre extends Model
{
    protected $table = 'solicitudes_sangre';

    protected $fillable = [
        'paciente_id', 'donante_asignado_id', 'tipo_sangre', 'cantidad_unidades',
        'solicitante_nombre', 'institucion', 'director_id', 'fecha_solicitud',
        'fecha_entrega', 'boleta_pdf_ruta', 'estado', 'indicaciones',
    ];

    protected function casts(): array
    {
        return ['fecha_solicitud' => 'date', 'fecha_entrega' => 'date'];
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'director_id');
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function donanteAsignado(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'donante_asignado_id');
    }
}

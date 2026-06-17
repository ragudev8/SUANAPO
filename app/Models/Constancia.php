<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Constancia extends Model
{
    protected $fillable = [
        'paciente_id', 'medico_id', 'tipo', 'asunto', 'contenido',
        'firma_medico_digital', 'sello_clinica', 'pdf_ruta',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'medico_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paciente extends Model
{
    protected $fillable = [
        'usuario_id', 'nombre', 'dni', 'fecha_nacimiento', 'sexo', 'grado_militar',
        'estado_civil', 'ocupacion', 'unidad_dependencia', 'numero_placa', 'tipo_sangre',
        'alergias', 'observaciones', 'telefono', 'celular', 'correo', 'direccion',
        'contacto_emergencia_nombre', 'contacto_emergencia_telefono',
        'responsable_nombre', 'responsable_parentesco',
    ];

    protected function casts(): array
    {
        return ['fecha_nacimiento' => 'date'];
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }

    public function getTipoPacienteLabelAttribute(): string
    {
        return config('anapo.patient_types.'.$this->grado_militar, str_replace('_', ' ', (string) $this->grado_militar));
    }

    public function getVinculoInstitucionalLabelAttribute(): string
    {
        return in_array($this->grado_militar, ['Civil', 'Beneficiario'], true)
            ? 'Civil'
            : 'Parte de la Policia';
    }

    public function getVinculoInstitucionalIconAttribute(): string
    {
        return $this->vinculo_institucional_label === 'Civil'
            ? 'bi-person'
            : 'bi-shield-check';
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function expediente(): HasOne
    {
        return $this->hasOne(ExpedienteMedico::class);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }

    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class);
    }

    public function libroVisitas(): HasMany
    {
        return $this->hasMany(LibroVisita::class);
    }

    public function incapacidades(): HasMany
    {
        return $this->hasMany(Incapacidad::class);
    }

    public function constancias(): HasMany
    {
        return $this->hasMany(Constancia::class);
    }

    public function examenesMedicos(): HasMany
    {
        return $this->hasMany(ExamenMedico::class);
    }

    public function donacionesSangre(): HasMany
    {
        return $this->hasMany(DonacionSangre::class, 'paciente_donante_id');
    }

    public function solicitudesSangre(): HasMany
    {
        return $this->hasMany(SolicitudSangre::class);
    }

    public function solicitudesComoDonante(): HasMany
    {
        return $this->hasMany(SolicitudSangre::class, 'donante_asignado_id');
    }
}

@extends('layouts.app')

@section('title', 'Consulta medica')

@section('content')
@php($preclinica = $visita->cita?->preclinica)
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-clipboard2-pulse me-2 text-primary"></i>Consulta medica</h1>
        <p class="text-secondary mb-0"><i class="bi bi-person-check me-1"></i>Visita #{{ $visita->numero_orden }} - {{ $visita->paciente->nombre }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('atenciones.board') }}"><i class="bi bi-kanban me-1"></i>Board</a>
</div>

@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<form class="card border-0 shadow-sm" method="post" action="{{ route('atenciones.consulta.store', $visita) }}">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person-badge me-1"></i>Medico</label>
                <select class="form-select" name="medico_id" required>
                    <option value="">Seleccione...</option>
                    @foreach($medicos as $medico)
                        <option value="{{ $medico->id }}" @selected(old('medico_id', $consulta->medico_id ?: auth()->id())==$medico->id)>{{ $medico->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8"><label class="form-label"><i class="bi bi-thermometer-half me-1"></i>Sintomas</label><input class="form-control" name="sintomas" value="{{ old('sintomas', $consulta->sintomas) }}" required></div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-clock-history me-1"></i>Duracion sintomas</label><input class="form-control" name="duracion_sintomas" value="{{ old('duracion_sintomas', $consulta->duracion_sintomas) }}"></div>
            <div class="col-md-3"><label class="form-label">Presion sistolica</label><input class="form-control" type="number" name="presion_sistolica" value="{{ old('presion_sistolica', $consulta->presion_sistolica ?: $preclinica?->presion_sistolica) }}"></div>
            <div class="col-md-3"><label class="form-label">Presion diastolica</label><input class="form-control" type="number" name="presion_diastolica" value="{{ old('presion_diastolica', $consulta->presion_diastolica ?: $preclinica?->presion_diastolica) }}"></div>
            <div class="col-md-3"><label class="form-label">Pulso</label><input class="form-control" type="number" name="pulso" value="{{ old('pulso', $consulta->pulso ?: $preclinica?->pulso) }}"></div>
            <div class="col-md-3"><label class="form-label">Temperatura</label><input class="form-control" type="number" step="0.01" name="temperatura" value="{{ old('temperatura', $consulta->temperatura ?: $preclinica?->temperatura) }}"></div>
            <div class="col-md-3"><label class="form-label">Peso</label><input class="form-control" type="number" step="0.01" name="peso" value="{{ old('peso', $consulta->peso ?: $preclinica?->peso) }}"></div>
            <div class="col-md-3"><label class="form-label">Talla</label><input class="form-control" type="number" step="0.01" name="talla" value="{{ old('talla', $consulta->talla ?: $preclinica?->talla) }}"></div>
            <div class="col-md-3"><label class="form-label">Firma digital</label><input class="form-control" name="firma_digital" value="{{ old('firma_digital', $consulta->firma_digital) }}"></div>
            <div class="col-12"><label class="form-label"><i class="bi bi-journal-medical me-1"></i>Notas medicas</label><textarea class="form-control" name="notas_medicas" rows="3" required>{{ old('notas_medicas', $consulta->notas_medicas) }}</textarea></div>
            <div class="col-md-6"><label class="form-label"><i class="bi bi-clipboard2-check me-1"></i>Diagnostico</label><textarea class="form-control" name="diagnostico" rows="3">{{ old('diagnostico', $consulta->diagnosticos->first()->descripcion ?? '') }}</textarea></div>
            <div class="col-md-6"><label class="form-label"><i class="bi bi-prescription2 me-1"></i>Tratamiento prescrito</label><textarea class="form-control" name="tratamiento_prescrito" rows="3">{{ old('tratamiento_prescrito', $consulta->tratamiento_prescrito) }}</textarea></div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex flex-column flex-sm-row justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('atenciones.visitas.show', $visita) }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success"><i class="bi bi-capsule me-1"></i>Guardar y enviar a farmacia</button>
    </div>
</form>
@endsection

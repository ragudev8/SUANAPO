@extends('layouts.app')

@section('title', 'Preclinica')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-heart-pulse me-2 text-danger"></i>Preclinica</h1>
        <p class="text-secondary mb-0"><i class="bi bi-person-check me-1"></i>Visita #{{ $visita->numero_orden }} - {{ $visita->paciente->nombre }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('atenciones.board') }}"><i class="bi bi-kanban me-1"></i>Board</a>
</div>

@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

@if ($preclinicaDelDia && ! $preclinica->exists)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <h2 class="h5 mb-1"><i class="bi bi-clock-history me-1 text-primary"></i>Preclinica del dia disponible</h2>
                    <p class="text-secondary mb-0">
                        <i class="bi bi-hospital me-1"></i>{{ $preclinicaDelDia->cita?->especialidad?->nombre ?? 'Sin servicio' }}
                        &middot; <i class="bi bi-clock me-1"></i>{{ $preclinicaDelDia->created_at?->format('H:i') }}
                        @if ($preclinicaDelDia->registradoPor)
                            &middot; <i class="bi bi-person-badge me-1"></i>{{ $preclinicaDelDia->registradoPor->nombre }}
                        @endif
                    </p>
                </div>
                <form method="post" action="{{ route('atenciones.preclinica.store', $visita) }}" class="d-grid d-lg-block">
                    @csrf
                    <button class="btn btn-primary" type="submit" name="usar_preclinica_id" value="{{ $preclinicaDelDia->id }}">
                        <i class="bi bi-check2-circle me-1"></i>Usar esta preclinica
                    </button>
                </form>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-6 col-md"><div class="border rounded p-2 h-100"><div class="small text-secondary">Presion</div><strong>{{ $preclinicaDelDia->presion_sistolica ?: 'N/D' }}/{{ $preclinicaDelDia->presion_diastolica ?: 'N/D' }}</strong></div></div>
                <div class="col-6 col-md"><div class="border rounded p-2 h-100"><div class="small text-secondary">Pulso</div><strong>{{ $preclinicaDelDia->pulso ?: 'N/D' }}</strong></div></div>
                <div class="col-6 col-md"><div class="border rounded p-2 h-100"><div class="small text-secondary">Temp.</div><strong>{{ $preclinicaDelDia->temperatura ?: 'N/D' }}</strong></div></div>
                <div class="col-6 col-md"><div class="border rounded p-2 h-100"><div class="small text-secondary">Peso</div><strong>{{ $preclinicaDelDia->peso ?: 'N/D' }}</strong></div></div>
                <div class="col-6 col-md"><div class="border rounded p-2 h-100"><div class="small text-secondary">Talla</div><strong>{{ $preclinicaDelDia->talla ?: 'N/D' }}</strong></div></div>
            </div>
        </div>
    </div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ route('atenciones.preclinica.store', $visita) }}">
    @csrf
    <div class="card-body">
        @if ($preclinicaDelDia && ! $preclinica->exists)
            <h2 class="h5 mb-3"><i class="bi bi-plus-circle me-1 text-success"></i>Tomar nueva preclinica</h2>
        @endif
        <div class="row g-3">
            <div class="col-6 col-md-3"><label class="form-label"><i class="bi bi-activity me-1"></i>Presion sistolica</label><input class="form-control" type="number" name="presion_sistolica" value="{{ old('presion_sistolica', $preclinica->presion_sistolica) }}"></div>
            <div class="col-6 col-md-3"><label class="form-label"><i class="bi bi-activity me-1"></i>Presion diastolica</label><input class="form-control" type="number" name="presion_diastolica" value="{{ old('presion_diastolica', $preclinica->presion_diastolica) }}"></div>
            <div class="col-6 col-md-2"><label class="form-label"><i class="bi bi-heart-pulse me-1"></i>Pulso</label><input class="form-control" type="number" name="pulso" value="{{ old('pulso', $preclinica->pulso) }}"></div>
            <div class="col-6 col-md-2"><label class="form-label"><i class="bi bi-thermometer-half me-1"></i>Temperatura</label><input class="form-control" type="number" step="0.01" name="temperatura" value="{{ old('temperatura', $preclinica->temperatura) }}"></div>
            <div class="col-6 col-md-1"><label class="form-label">Peso</label><input class="form-control" type="number" step="0.01" name="peso" value="{{ old('peso', $preclinica->peso) }}"></div>
            <div class="col-6 col-md-1"><label class="form-label">Talla</label><input class="form-control" type="number" step="0.01" name="talla" value="{{ old('talla', $preclinica->talla) }}"></div>
            <div class="col-12"><label class="form-label"><i class="bi bi-card-text me-1"></i>Notas iniciales</label><textarea class="form-control" name="notas_iniciales" rows="3">{{ old('notas_iniciales', $preclinica->notas_iniciales) }}</textarea></div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('atenciones.visitas.show', $visita) }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success"><i class="bi bi-person-badge me-1"></i>Guardar y enviar a medico</button>
    </div>
</form>
@endsection

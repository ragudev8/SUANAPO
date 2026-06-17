@extends('layouts.app')

@section('title', $retroalimentacion->asunto)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-chat-square-text me-2 text-primary"></i>{{ $retroalimentacion->asunto }}</h1>
        <p class="text-secondary mb-0"><i class="bi bi-person me-1"></i>{{ $retroalimentacion->usuario?->nombre ?? 'Usuario' }} - <i class="bi bi-clock me-1"></i>{{ $retroalimentacion->created_at?->format('d/m/Y H:i') }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('retroalimentacion.index') }}"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge text-bg-dark"><i class="bi bi-grid-3x3-gap me-1"></i>{{ config('anapo.modules.'.$retroalimentacion->modulo, $retroalimentacion->modulo ?: 'Sistema general') }}</span>
                    <span class="badge text-bg-light"><i class="bi bi-tags me-1"></i>{{ ucfirst(str_replace('_', ' ', $retroalimentacion->tipo)) }}</span>
                    <span class="badge text-bg-light"><i class="bi bi-flag me-1"></i>Prioridad {{ $retroalimentacion->prioridad }}</span>
                    <span class="badge {{ match($retroalimentacion->estado) {
                        'pendiente' => 'text-bg-warning',
                        'revisando' => 'text-bg-primary',
                        'aceptada' => 'text-bg-success',
                        default => 'text-bg-secondary',
                    } }}"><i class="bi {{ match($retroalimentacion->estado) {
                        'pendiente' => 'bi-hourglass-split',
                        'revisando' => 'bi-search',
                        'aceptada' => 'bi-check-circle',
                        default => 'bi-archive',
                    } }} me-1"></i>{{ ucfirst($retroalimentacion->estado) }}</span>
                </div>
                <h2 class="h6 text-uppercase text-secondary"><i class="bi bi-card-text me-1"></i>Detalle enviado</h2>
                <p class="mb-0" style="white-space: pre-line;">{{ $retroalimentacion->mensaje }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-secondary"><i class="bi bi-reply me-1"></i>Respuesta administrativa</h2>
                <p class="mb-0" style="white-space: pre-line;">{{ $retroalimentacion->respuesta_admin ?: 'Sin respuesta todavia.' }}</p>
                @if ($retroalimentacion->revisadoPor)
                    <div class="small text-secondary mt-2">
                        <i class="bi bi-person-check me-1"></i>Revisado por {{ $retroalimentacion->revisadoPor->nombre }} el {{ $retroalimentacion->revisado_en?->format('d/m/Y H:i') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (auth()->user()->esAdmin())
        <div class="col-lg-4">
            <form class="card border-0 shadow-sm" method="post" action="{{ route('retroalimentacion.update', $retroalimentacion) }}">
                @csrf
                @method('put')
                <div class="card-body">
                    <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-sliders me-1"></i>Gestionar</h2>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-flag me-1"></i>Estado</label>
                        <select class="form-select" name="estado" required>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado }}" @selected(old('estado', $retroalimentacion->estado) === $estado)>{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label"><i class="bi bi-chat-left-text me-1"></i>Respuesta o nota</label>
                        <textarea class="form-control" name="respuesta_admin" rows="6">{{ old('respuesta_admin', $retroalimentacion->respuesta_admin) }}</textarea>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-end">
                    <button class="btn btn-success" type="submit"><i class="bi bi-check2-circle me-1"></i>Guardar</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

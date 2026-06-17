@extends('layouts.app')

@section('title', $receta->folio_unico)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-prescription2 me-2 text-primary"></i>Receta {{ $receta->folio_unico }}</h1>
        <p class="text-secondary mb-0">
            <i class="bi bi-person me-1"></i>{{ $receta->paciente?->nombre ?? 'Paciente N/D' }}
            @if($receta->paciente?->dni)
                - DNI {{ $receta->paciente->dni }}
            @endif
            <span class="mx-1">|</span>
            <i class="bi bi-person-badge me-1"></i>{{ $receta->medico?->nombre ?? 'Medico N/D' }}
        </p>
    </div>
    <div class="d-flex gap-2">
        @php($locked = $receta->detalles->contains(fn ($detalle) => $detalle->dispensado))
        @if (auth()->user()->canModule('recetas', 'edit') && (! $locked || auth()->user()->esAdmin()))<a class="btn btn-outline-primary" href="{{ route('recetas.edit', $receta) }}"><i class="bi bi-pencil"></i></a>@endif
        <a class="btn btn-outline-secondary" href="{{ route('recetas.index') }}"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
        <h2 class="h5"><i class="bi bi-info-circle me-1 text-primary"></i>Datos</h2>
        <dl class="row mb-0">
            <dt class="col-5">Estado</dt><dd class="col-7">{{ ucfirst($receta->estado) }}</dd>
            <dt class="col-5">Emision</dt><dd class="col-7">{{ optional($receta->fecha_emision)->format('d/m/Y') }}</dd>
            <dt class="col-5">Vence</dt><dd class="col-7">{{ optional($receta->fecha_vencimiento)->format('d/m/Y') ?: 'N/D' }}</dd>
            <dt class="col-5">QR</dt><dd class="col-7 text-break">{{ $receta->codigo_qr }}</dd>
        </dl>
    </div></div></div>
    <div class="col-lg-8"><div class="card border-0 shadow-sm h-100"><div class="card-body">
        <h2 class="h5"><i class="bi bi-capsule me-1 text-success"></i>Medicamentos</h2>
        @forelse ($receta->detalles as $detalle)
            <div class="border-bottom py-2">
                <strong>{{ $detalle->medicamento?->nombre }}</strong>
                <div class="text-secondary">{{ $detalle->dosis }} - {{ $detalle->frecuencia }} - {{ $detalle->cantidad_dias }} dias - Cantidad {{ $detalle->cantidad_medicamento }}</div>
            </div>
        @empty
            <p class="text-secondary mb-0">Sin medicamentos registrados.</p>
        @endforelse
    </div></div></div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dispensacion')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-capsule me-2 text-success"></i>Dispensacion</h1>
        <p class="text-secondary mb-0"><i class="bi bi-person-check me-1"></i>Visita #{{ $visita->numero_orden }} - {{ $visita->paciente->nombre }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('atenciones.board') }}"><i class="bi bi-kanban me-1"></i>Board</a>
</div>

@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-prescription2 me-1 text-primary"></i>Receta para dispensar</h2>
                @if(! $consulta)
                    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i>Debe registrar la consulta medica antes de dispensar.</div>
                @endif
                @if($receta)
                    <p class="text-secondary">Folio {{ $receta->folio_unico }} - {{ ucfirst($receta->estado) }}</p>
                    @forelse($receta->detalles as $detalle)
                        <div class="border-bottom py-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                <div>
                                    <strong>{{ $detalle->medicamento?->nombre }}</strong>
                                    <div class="text-secondary">
                                        {{ $detalle->dosis ?: 'Sin dosis' }} - {{ $detalle->frecuencia ?: 'Sin frecuencia' }} - {{ $detalle->cantidad_dias ?: 'N/D' }} dias
                                    </div>
                                    <div class="small text-secondary">
                                        <i class="bi bi-boxes me-1"></i>Stock {{ $detalle->medicamento?->cantidad_stock ?? 'N/D' }}
                                        <span class="mx-1">-</span>
                                        <i class="bi bi-capsule me-1"></i>Cantidad indicada {{ $detalle->cantidad_medicamento }}
                                    </div>
                                </div>
                                <div class="text-md-end">
                                    <span class="badge {{ $detalle->dispensado ? 'text-bg-success' : 'text-bg-warning' }}">
                                        <i class="bi {{ $detalle->dispensado ? 'bi-check-circle' : 'bi-hourglass-split' }} me-1"></i>{{ $detalle->dispensado ? 'Dispensado' : 'Pendiente' }}
                                    </span>
                                    @if(! $detalle->dispensado && $consulta)
                                        <form class="mt-2" method="post" action="{{ route('atenciones.dispensacion.store', $visita) }}" onsubmit="return confirm('Dispensar este medicamento y descontar inventario?')">
                                            @csrf
                                            <input type="hidden" name="detalle_id" value="{{ $detalle->id }}">
                                            <button class="btn btn-sm btn-success"><i class="bi bi-check2-circle me-1"></i>Dispensar</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-secondary mb-0">La receta no tiene medicamentos indicados.</p>
                    @endforelse
                @else
                    <p class="text-secondary mb-0">Aun no hay receta generada para esta consulta. Primero cree la receta desde el modulo Recetas.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-info-circle me-1 text-primary"></i>Regla del flujo</h2>
                <p class="text-secondary mb-0">La receta indica el tratamiento. Farmacia solo confirma la entrega y descuenta inventario.</p>
            </div>
            @if($consulta && $visita->estado !== 'finalizado')
                <div class="card-footer bg-white text-end">
                    <form method="post" action="{{ route('atenciones.cerrar', $visita) }}" onsubmit="return confirm('Finalizar esta atencion?')">
                        @csrf
                        <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Finalizar atencion</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

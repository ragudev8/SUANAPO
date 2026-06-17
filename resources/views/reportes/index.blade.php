@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Reportes</h1>
        <p class="text-secondary mb-0">Resumen mensual del sistema.</p>
    </div>
    <form class="d-flex gap-2" method="get">
        <input class="form-control" type="number" name="month" min="1" max="12" value="{{ $month }}" aria-label="Mes">
        <input class="form-control" type="number" name="year" min="2024" max="2100" value="{{ $year }}" aria-label="Ano">
        <button class="btn btn-success"><i class="bi bi-funnel me-1"></i>Generar</button>
        <a class="btn btn-outline-primary" href="{{ route('reportes.descargar', ['month' => $month, 'year' => $year]) }}">
            <i class="bi bi-download"></i>
        </a>
    </form>
</div>

<div class="row g-3 mb-3">
    @foreach ($totals as $label => $total)
        <div class="col-6 col-xl">
            <div class="card metric border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small"><i class="bi bi-bar-chart-line me-1"></i>{{ $label }}</div>
                    <div class="h2 mb-0">{{ $total }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-pie-chart me-1 text-primary"></i>Pacientes por tipo</h2>
                @forelse ($porGrado as $item)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ config('anapo.patient_types.'.$item->grado_militar, str_replace('_', ' ', $item->grado_militar)) }}</span>
                        <strong>{{ $item->total }}</strong>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin datos.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-activity me-1 text-primary"></i>Visitas por estado</h2>
                @forelse ($visitasPorEstado as $item)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ str_replace('_', ' ', $item->estado) }}</span>
                        <strong>{{ $item->total }}</strong>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin visitas en el periodo.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-exclamation-triangle me-1 text-warning"></i>Stock bajo calculado</h2>
                @forelse ($stockBajo as $medicamento)
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between gap-2">
                            <span>{{ $medicamento->nombre }}</span>
                            <strong>{{ $medicamento->cantidad_stock }} en stock</strong>
                        </div>
                        <div class="small text-secondary">
                            <i class="bi bi-boxes me-1"></i>Minimo {{ $medicamento->cantidad_minima }}
                            <span class="mx-1">-</span>
                            <i class="bi bi-capsule me-1"></i>Entregado mes {{ $medicamento->dispensado_mes }}
                            <span class="mx-1">-</span>
                            Total entregado {{ $medicamento->dispensado_total }}
                        </div>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin medicamentos bajo minimo.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

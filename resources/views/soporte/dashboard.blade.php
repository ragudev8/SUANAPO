@extends('layouts.app')

@section('title', 'Soporte TI')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-pc-display me-2 text-primary"></i>Soporte TI</h1>
        <p class="text-secondary mb-0">Base lista para tickets, equipos y reportes tecnicos.</p>
    </div>
</div>

<div class="row g-3 mb-3">
    @foreach ($stats as $label => $total)
        <div class="col-6 col-xl-3">
            <div class="card metric border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small"><i class="bi bi-bar-chart-line me-1"></i>{{ $label }}</div>
                    <div class="h2 mb-0">{{ $total }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5"><i class="bi bi-diagram-3 me-1 text-primary"></i>Modulos preparados</h2>
        <div class="row g-2">
            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold"><i class="bi bi-ticket-perforated me-1"></i>Tickets</div>
                    <div class="text-secondary small">Solicitudes, asignaciones, prioridades y estados.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold"><i class="bi bi-laptop me-1"></i>Equipos</div>
                    <div class="text-secondary small">Inventario de computadoras, impresoras y perifericos.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i>Reportes</div>
                    <div class="text-secondary small">Indicadores calculados cuando existan datos reales.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

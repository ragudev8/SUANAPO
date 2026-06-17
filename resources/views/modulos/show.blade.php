@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>{{ $title }}</h1>
        <p class="text-secondary mb-0">Modulo habilitado por permisos. La funcionalidad detallada se ira completando por flujo.</p>
    </div>
</div>

<div class="row g-3">
    @foreach (['view' => 'Ver', 'create' => 'Crear', 'edit' => 'Editar', 'delete' => 'Eliminar'] as $action => $label)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi {{ auth()->user()->canModule($module, $action) ? 'bi-check-circle-fill text-success' : 'bi-dash-circle text-secondary' }} fs-3"></i>
                    <div class="fw-semibold mt-2">{{ $label }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

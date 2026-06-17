@extends('layouts.app')

@section('title', $medicamento->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-capsule me-2 text-success"></i>{{ $medicamento->nombre }}</h1>
        <p class="text-secondary mb-0">{{ $medicamento->presentacion }} {{ $medicamento->dosis }}</p>
    </div>
    <div class="d-flex gap-2">
        @if (auth()->user()->canModule('medicamentos', 'edit'))
            <a class="btn btn-outline-primary" href="{{ route('medicamentos.edit', $medicamento) }}"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endif
        @if (auth()->user()->canModule('medicamentos', 'delete'))
            <form method="post" action="{{ route('medicamentos.destroy', $medicamento) }}" onsubmit="return confirm('Eliminar este medicamento?')">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Eliminar</button>
            </form>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3"><i class="bi bi-boxes me-1"></i>Stock</dt><dd class="col-sm-9">{{ $medicamento->cantidad_stock }}</dd>
            <dt class="col-sm-3"><i class="bi bi-exclamation-triangle me-1"></i>Stock minimo</dt><dd class="col-sm-9">{{ $medicamento->cantidad_minima }}</dd>
            <dt class="col-sm-3"><i class="bi bi-calendar2-x me-1"></i>Vencimiento</dt><dd class="col-sm-9">{{ optional($medicamento->fecha_vencimiento)->format('d/m/Y') ?? 'N/D' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-upc-scan me-1"></i>Lote</dt><dd class="col-sm-9">{{ $medicamento->lote ?: 'N/D' }}</dd>
        </dl>
    </div>
</div>
@endsection

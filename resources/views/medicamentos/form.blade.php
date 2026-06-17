@extends('layouts.app')

@section('title', 'Editar medicamento')

@section('content')
<h1 class="h3 mb-3"><i class="bi bi-pencil-square me-2 text-primary"></i>Editar medicamento</h1>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ route('medicamentos.update', $medicamento) }}">
    @csrf
    @method('put')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label"><i class="bi bi-capsule me-1"></i>Nombre</label><input class="form-control" name="nombre" value="{{ old('nombre', $medicamento->nombre) }}" required></div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-box-seam me-1"></i>Presentacion</label><input class="form-control" name="presentacion" value="{{ old('presentacion', $medicamento->presentacion) }}"></div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-prescription2 me-1"></i>Dosis</label><input class="form-control" name="dosis" value="{{ old('dosis', $medicamento->dosis) }}"></div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-boxes me-1"></i>Stock</label><input class="form-control" type="number" name="cantidad_stock" min="0" value="{{ old('cantidad_stock', $medicamento->cantidad_stock) }}" required></div>
            <div class="col-md-3"><label class="form-label">Minimo</label><input class="form-control" type="number" name="cantidad_minima" min="0" value="{{ old('cantidad_minima', $medicamento->cantidad_minima) }}" required></div>
            <div class="col-md-3"><label class="form-label">Vencimiento</label><input class="form-control" type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', optional($medicamento->fecha_vencimiento)->format('Y-m-d')) }}"></div>
            <div class="col-md-3"><label class="form-label">Lote</label><input class="form-control" name="lote" value="{{ old('lote', $medicamento->lote) }}"></div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('medicamentos.show', $medicamento) }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button>
    </div>
</form>
@endsection

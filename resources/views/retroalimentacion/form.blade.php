@extends('layouts.app')

@section('title', 'Enviar retroalimentacion')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0"><i class="bi bi-send-plus me-2 text-success"></i>Enviar retroalimentacion</h1>
        <p class="text-secondary mb-0">Cuente que se puede mejorar, corregir o agregar al sistema.</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ route('retroalimentacion.store') }}">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-grid-3x3-gap me-1"></i>Modulo relacionado</label>
                <select class="form-select" name="modulo">
                    <option value="">Sistema general</option>
                    @foreach ($modulos as $key => $label)
                        <option value="{{ $key }}" @selected(old('modulo', $retroalimentacion->modulo) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-tags me-1"></i>Tipo</label>
                <select class="form-select" name="tipo" required>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo }}" @selected(old('tipo', $retroalimentacion->tipo) === $tipo)>{{ ucfirst(str_replace('_', ' ', $tipo)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-flag me-1"></i>Prioridad</label>
                <select class="form-select" name="prioridad" required>
                    @foreach ($prioridades as $prioridad)
                        <option value="{{ $prioridad }}" @selected(old('prioridad', $retroalimentacion->prioridad) === $prioridad)>{{ ucfirst($prioridad) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label"><i class="bi bi-lightbulb me-1"></i>Asunto</label>
                <input class="form-control" name="asunto" value="{{ old('asunto', $retroalimentacion->asunto) }}" maxlength="180" required placeholder="Ej. Agregar busqueda por numero de placa">
            </div>
            <div class="col-12">
                <label class="form-label"><i class="bi bi-card-text me-1"></i>Detalle</label>
                <textarea class="form-control" name="mensaje" rows="6" maxlength="3000" required placeholder="Explique que necesita, donde ocurre o que le gustaria mejorar.">{{ old('mensaje', $retroalimentacion->mensaje) }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('retroalimentacion.index') }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success" type="submit"><i class="bi bi-send me-1"></i>Enviar</button>
    </div>
</form>
@endsection

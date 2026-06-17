@extends('layouts.app')

@section('title', $receta->exists ? 'Editar receta' : 'Nueva receta')

@section('content')
<h1 class="h3 mb-3"><i class="bi {{ $receta->exists ? 'bi-pencil-square' : 'bi-prescription2' }} me-2 text-primary"></i>{{ $receta->exists ? 'Editar receta' : 'Nueva receta' }}</h1>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ $receta->exists ? route('recetas.update', $receta) : route('recetas.store') }}">
    @csrf
    @if ($receta->exists) @method('put') @endif
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-6">
                <label class="form-label"><i class="bi bi-person-vcard me-1"></i>Paciente / consulta</label>
                <select class="form-select" name="consulta_id" required>
                    <option value="">Seleccione el paciente atendido...</option>
                    @foreach ($consultas as $consulta)
                        <option value="{{ $consulta->id }}" @selected(old('consulta_id', $receta->consulta_id)==$consulta->id)>
                            {{ $consulta->paciente?->nombre }} - {{ $consulta->paciente?->dni }} | Consulta #{{ $consulta->id }} | {{ $consulta->medico?->nombre }} | {{ $consulta->created_at->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">La receta quedara guardada en el expediente del paciente seleccionado aqui.</div>
            </div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-qr-code me-1"></i>Folio</label><input class="form-control" name="folio_unico" value="{{ old('folio_unico', $receta->folio_unico ?: 'RX-'.now()->format('YmdHis')) }}" required></div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-activity me-1"></i>Estado</label><select class="form-select" name="estado">@foreach(['activa','vencida','cancelada'] as $v)<option @selected(old('estado', $receta->estado ?: 'activa')===$v)>{{ $v }}</option>@endforeach @if($receta->estado === 'surtida')<option value="surtida" selected>surtida</option>@endif</select></div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-calendar-event me-1"></i>Emision</label><input class="form-control" type="date" name="fecha_emision" value="{{ old('fecha_emision', optional($receta->fecha_emision)->format('Y-m-d') ?: now()->toDateString()) }}" required></div>
            <div class="col-md-3"><label class="form-label">Vencimiento</label><input class="form-control" type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', optional($receta->fecha_vencimiento)->format('Y-m-d')) }}"></div>
            <div class="col-md-3"><label class="form-label">Codigo QR</label><input class="form-control" name="codigo_qr" value="{{ old('codigo_qr', $receta->codigo_qr) }}"></div>
            <div class="col-md-3"><label class="form-label">Firma digital</label><input class="form-control" name="firma_digital" value="{{ old('firma_digital', $receta->firma_digital) }}"></div>
            <div class="col-12"><label class="form-label">Notas</label><textarea class="form-control" name="notas" rows="2">{{ old('notas', $receta->notas) }}</textarea></div>
        </div>

        <hr>
        <h2 class="h5"><i class="bi bi-capsule me-1 text-success"></i>Detalle de medicamento</h2>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Medicamento</label><select class="form-select" name="medicamento_id"><option value="">Sin detalle</option>@foreach($medicamentos as $m)<option value="{{ $m->id }}" @selected(old('medicamento_id', $detalle->medicamento_id)==$m->id)>{{ $m->nombre }} {{ $m->dosis }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label">Dosis</label><input class="form-control" name="dosis" value="{{ old('dosis', $detalle->dosis) }}"></div>
            <div class="col-md-2"><label class="form-label">Frecuencia</label><input class="form-control" name="frecuencia" value="{{ old('frecuencia', $detalle->frecuencia) }}"></div>
            <div class="col-md-2"><label class="form-label">Dias</label><input class="form-control" type="number" min="1" name="cantidad_dias" value="{{ old('cantidad_dias', $detalle->cantidad_dias) }}"></div>
            <div class="col-md-2"><label class="form-label">Cantidad</label><input class="form-control" type="number" min="1" name="cantidad_medicamento" value="{{ old('cantidad_medicamento', $detalle->cantidad_medicamento) }}"></div>
            <div class="col-12">
                <div class="alert alert-info mb-0"><i class="bi bi-info-circle me-1"></i>La receta no descuenta inventario. El stock se descuenta cuando farmacia dispensa la medicina.</div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('recetas.index') }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button>
    </div>
</form>
@endsection

@extends('layouts.app')

@section('title', 'Editar visita')

@section('content')
<h1 class="h3 mb-3"><i class="bi bi-pencil-square me-2 text-primary"></i>Editar visita #{{ $visita->numero_orden }}</h1>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ route('atenciones.visitas.update', $visita) }}">
    @csrf
    @method('put')
    <div class="card-body row g-3">
        <div class="col-lg-4">
            <label class="form-label"><i class="bi bi-person me-1"></i>Paciente</label>
            <x-search-select
                name="paciente_id"
                :items="$pacientes->map(fn ($paciente) => ['value' => (string) $paciente->id, 'label' => $paciente->nombre.' - '.$paciente->dni])->values()->all()"
                :value="old('paciente_id', $visita->paciente_id)"
                placeholder="Escriba nombre o DNI del paciente"
                required
            />
        </div>
        <div class="col-sm-6 col-lg-2">
            <label class="form-label"><i class="bi bi-arrow-left-right me-1"></i>Tipo</label>
            @php($tipoConsultaActual = old('tipo_consulta', $visita->cita?->tipo_consulta ?? 'sin_asignar'))
            <select class="form-select" name="tipo_consulta" required>
                <option value="sin_asignar" @selected($tipoConsultaActual === 'sin_asignar')>Sin asignar</option>
                <option value="interna" @selected($tipoConsultaActual === 'interna')>Interna</option>
                <option value="externa" @selected($tipoConsultaActual === 'externa')>Externa</option>
            </select>
        </div>
        <div class="col-sm-6 col-lg-3">
            <label class="form-label"><i class="bi bi-hospital me-1"></i>A que viene</label>
            <x-search-select
                name="especialidad_id"
                :items="$especialidades->map(fn ($especialidad) => ['value' => (string) $especialidad->id, 'label' => $especialidad->nombre])->values()->all()"
                :value="old('especialidad_id', $visita->cita?->especialidad_id)"
                placeholder="Odontologia, ginecologia..."
                empty-label="Sin asignar"
            />
        </div>
        <div class="col-md-3">
            <label class="form-label"><i class="bi bi-activity me-1"></i>Estado</label>
            <select class="form-select" name="estado" required>
                @foreach (['registrado','preclinica','esperando_medico','en_consulta','en_farmacia','en_procedimiento','finalizado'] as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $visita->estado) === $estado)>{{ str_replace('_', ' ', $estado) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('atenciones.visitas.show', $visita) }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button>
    </div>
</form>
@endsection

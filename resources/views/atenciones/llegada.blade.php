@extends('layouts.app')

@section('title', 'Registrar llegada')

@section('content')
<h1 class="h3 mb-3"><i class="bi bi-person-plus me-2 text-success"></i>Registrar llegada</h1>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ route('atenciones.llegada.store') }}">
    @csrf
    <div class="card-body row g-3">
        <div class="col-lg-6">
            <label class="form-label"><i class="bi bi-person me-1"></i>Paciente</label>
            <x-search-select
                name="paciente_id"
                :items="$pacientes->map(fn ($paciente) => ['value' => (string) $paciente->id, 'label' => $paciente->nombre.' - '.$paciente->dni])->values()->all()"
                :value="old('paciente_id')"
                placeholder="Escriba nombre o DNI del paciente"
                required
            />
        </div>
        <div class="col-sm-6 col-lg-3">
            <label class="form-label"><i class="bi bi-arrow-left-right me-1"></i>Tipo de consulta</label>
            <select class="form-select" name="tipo_consulta" required>
                <option value="sin_asignar" @selected(old('tipo_consulta', 'sin_asignar') === 'sin_asignar')>Sin asignar</option>
                <option value="interna" @selected(old('tipo_consulta') === 'interna')>Interna</option>
                <option value="externa" @selected(old('tipo_consulta') === 'externa')>Externa</option>
            </select>
        </div>
        <div class="col-sm-6 col-lg-3">
            <label class="form-label"><i class="bi bi-hospital me-1"></i>A que viene</label>
            <x-search-select
                name="especialidad_id"
                :items="$especialidades->map(fn ($especialidad) => ['value' => (string) $especialidad->id, 'label' => $especialidad->nombre])->values()->all()"
                :value="old('especialidad_id')"
                placeholder="Odontologia, ginecologia..."
                empty-label="Sin asignar"
            />
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <button class="btn btn-success" type="submit"><i class="bi bi-check2-circle me-1"></i>Registrar</button>
    </div>
</form>
@endsection

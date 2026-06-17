@extends('layouts.app')

@section('title', $paciente->exists ? 'Editar paciente' : 'Nuevo paciente')

@section('content')
@php($expediente = $paciente->expediente)

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi {{ $paciente->exists ? 'bi-pencil-square' : 'bi-person-plus' }} me-2 text-primary"></i>{{ $paciente->exists ? 'Editar paciente' : 'Nuevo paciente' }}</h1>
        <p class="text-secondary mb-0">Ficha completa con contacto, datos institucionales y antecedentes.</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ $paciente->exists ? route('pacientes.show', $paciente) : route('pacientes.index') }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ $paciente->exists ? route('pacientes.update', $paciente) : route('pacientes.store') }}">
    @csrf
    @if ($paciente->exists) @method('put') @endif

    <div class="card-body">
        <section class="mb-4">
            <h2 class="h5 mb-3"><i class="bi bi-person-vcard me-1 text-primary"></i>Identificacion</h2>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label"><i class="bi bi-link-45deg me-1"></i>Vincular con usuario del sistema</label>
                    <x-search-select
                        name="usuario_id"
                        :items="$usuarios->map(fn ($usuario) => [
                            'value' => (string) $usuario->id,
                            'label' => $usuario->nombre.' - '.$usuario->rol_label.' - '.($usuario->dni ?: $usuario->email),
                        ])->values()->all()"
                        :value="old('usuario_id', $paciente->usuario_id)"
                        placeholder="Buscar usuario por nombre, DNI o correo"
                        empty-label="Sin usuario vinculado"
                    />
                    <div class="form-text">Use esta opcion cuando la persona ya tiene acceso a SUANAPO y tambien necesita expediente clinico.</div>
                </div>
                <div class="col-lg-5">
                    <label class="form-label">Nombre completo</label>
                    <input class="form-control" name="nombre" value="{{ old('nombre', $paciente->nombre) }}" required>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label">DNI</label>
                    <input class="form-control" name="dni" value="{{ old('dni', $paciente->dni) }}" required>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Nacimiento</label>
                    <input class="form-control" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($paciente->fecha_nacimiento)->format('Y-m-d')) }}" required>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Sexo</label>
                    <select class="form-select" name="sexo" required>
                        @foreach(['M','F','Otro'] as $v)
                            <option @selected(old('sexo', $paciente->sexo)===$v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label">Estado civil</label>
                    <select class="form-select" name="estado_civil">
                        <option value="">N/D</option>
                        @foreach(['Soltero','Casado','Union libre','Divorciado','Viudo'] as $v)
                            <option value="{{ $v }}" @selected(old('estado_civil', $paciente->estado_civil)===$v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <h2 class="h5 mb-3"><i class="bi bi-telephone me-1 text-primary"></i>Contacto</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label">Telefono fijo</label>
                    <input class="form-control" name="telefono" value="{{ old('telefono', $paciente->telefono) }}">
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label">Celular</label>
                    <input class="form-control" name="celular" value="{{ old('celular', $paciente->celular) }}">
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Correo</label>
                    <input class="form-control" type="email" name="correo" value="{{ old('correo', $paciente->correo) }}">
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Direccion</label>
                    <input class="form-control" name="direccion" value="{{ old('direccion', $paciente->direccion) }}">
                </div>
            </div>
        </section>

        <section class="mb-4">
            <h2 class="h5 mb-3"><i class="bi bi-shield-check me-1 text-primary"></i>Datos ANAPO</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label"><i class="bi bi-person-badge me-1"></i>Tipo de paciente</label>
                    <select class="form-select" name="grado_militar" required>
                        @foreach(config('anapo.patient_types') as $v => $label)
                            <option value="{{ $v }}" @selected(old('grado_militar', $paciente->grado_militar)===$v)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Civil o beneficiario se mostrara como Civil; los demas como parte de la Policia.</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label">Placa</label>
                    <input class="form-control" name="numero_placa" value="{{ old('numero_placa', $paciente->numero_placa) }}">
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Ocupacion o cargo</label>
                    <input class="form-control" name="ocupacion" value="{{ old('ocupacion', $paciente->ocupacion) }}">
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Unidad o dependencia</label>
                    <input class="form-control" name="unidad_dependencia" value="{{ old('unidad_dependencia', $paciente->unidad_dependencia) }}">
                </div>
            </div>
        </section>

        <section class="mb-4">
            <h2 class="h5 mb-3"><i class="bi bi-heart-pulse me-1 text-danger"></i>Salud base</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label">Tipo sangre</label>
                    <select class="form-select" name="tipo_sangre">
                        <option value="">N/D</option>
                        @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $v)
                            <option @selected(old('tipo_sangre', $paciente->tipo_sangre)===$v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-9">
                    <label class="form-label">Alergias</label>
                    <input class="form-control" name="alergias" value="{{ old('alergias', $paciente->alergias) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Antecedentes familiares</label>
                    <textarea class="form-control" name="antecedentes_familiares" rows="3">{{ old('antecedentes_familiares', $expediente?->antecedentes_familiares) }}</textarea>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Antecedentes personales</label>
                    <textarea class="form-control" name="antecedentes_personales" rows="3">{{ old('antecedentes_personales', $expediente?->antecedentes_personales) }}</textarea>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Antecedentes quirurgicos</label>
                    <textarea class="form-control" name="antecedentes_quirurgicos" rows="3">{{ old('antecedentes_quirurgicos', $expediente?->antecedentes_quirurgicos) }}</textarea>
                </div>
            </div>
        </section>

        <section>
            <h2 class="h5 mb-3"><i class="bi bi-exclamation-triangle me-1 text-warning"></i>Emergencia y notas</h2>
            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label">Contacto de emergencia</label>
                    <input class="form-control" name="contacto_emergencia_nombre" value="{{ old('contacto_emergencia_nombre', $paciente->contacto_emergencia_nombre) }}">
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label">Telefono emergencia</label>
                    <input class="form-control" name="contacto_emergencia_telefono" value="{{ old('contacto_emergencia_telefono', $paciente->contacto_emergencia_telefono) }}">
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Responsable</label>
                    <input class="form-control" name="responsable_nombre" value="{{ old('responsable_nombre', $paciente->responsable_nombre) }}">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Parentesco</label>
                    <input class="form-control" name="responsable_parentesco" value="{{ old('responsable_parentesco', $paciente->responsable_parentesco) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="3">{{ old('observaciones', $paciente->observaciones) }}</textarea>
                </div>
            </div>
        </section>
    </div>

    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ $paciente->exists ? route('pacientes.show', $paciente) : route('pacientes.index') }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success" type="submit"><i class="bi bi-check2-circle me-1"></i>Guardar</button>
    </div>
</form>
@endsection

@extends('layouts.app')

@section('title', $usuario->exists ? 'Editar usuario' : 'Nuevo usuario')

@section('content')
<h1 class="h3 mb-3"><i class="bi {{ $usuario->exists ? 'bi-person-gear' : 'bi-person-plus' }} me-2 text-primary"></i>{{ $usuario->exists ? 'Editar usuario' : 'Nuevo usuario' }}</h1>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form class="card border-0 shadow-sm" method="post" action="{{ $usuario->exists ? route('usuarios.update', $usuario) : route('usuarios.store') }}">
    @csrf
    @if ($usuario->exists) @method('put') @endif

    <div class="card-body">
        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-shield-lock me-1"></i>Acceso al sistema</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-person me-1"></i>Nombre</label>
                <input class="form-control" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-envelope me-1"></i>Correo</label>
                <input class="form-control" type="email" name="email" value="{{ old('email', $usuario->email) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person-vcard me-1"></i>DNI</label>
                <input class="form-control" name="dni" value="{{ old('dni', $usuario->dni) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-hash me-1"></i>No. empleado</label>
                <input class="form-control" name="numero_empleado" value="{{ old('numero_empleado', $usuario->numero_empleado) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person-gear me-1"></i>Rol</label>
                <select class="form-select" name="rol" required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol }}" @selected(old('rol', $usuario->rol) === $rol)>{{ \App\Models\Usuario::roleLabel($rol) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-key me-1"></i>{{ $usuario->exists ? 'Nueva contrasena' : 'Contrasena temporal' }}</label>
                <input class="form-control" type="password" name="password" @required(! $usuario->exists) minlength="8">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" value="1" @checked(old('activo', $usuario->activo))>
                    <label class="form-check-label" for="activo">Usuario activo</label>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <h2 class="h6 text-uppercase text-secondary mb-3"><i class="bi bi-briefcase me-1"></i>Datos laborales</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person-badge me-1"></i>Cargo</label>
                <input class="form-control" name="cargo" value="{{ old('cargo', $usuario->cargo) }}" placeholder="Jefe de clinica, medico, auditor...">
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-diagram-3 me-1"></i>Area o departamento</label>
                <input class="form-control" name="area_departamento" value="{{ old('area_departamento', $usuario->area_departamento) }}" placeholder="Clinica, farmacia, auditoria...">
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-building me-1"></i>Unidad asignada</label>
                <input class="form-control" name="unidad_asignada" value="{{ old('unidad_asignada', $usuario->unidad_asignada) }}" placeholder="ANAPO, preclinica, administracion...">
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-clock me-1"></i>Turno</label>
                <select class="form-select" name="turno">
                    <option value="">Seleccione...</option>
                    @foreach (['Matutino', 'Vespertino', 'Nocturno', 'Administrativo', 'Rotativo'] as $turno)
                        <option value="{{ $turno }}" @selected(old('turno', $usuario->turno) === $turno)>{{ $turno }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-calendar-event me-1"></i>Fecha de ingreso</label>
                <input class="form-control" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', optional($usuario->fecha_ingreso)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-hospital me-1"></i>Servicio asignado</label>
                <x-search-select
                    name="especialidad_id"
                    :items="$especialidades->map(fn ($especialidad) => ['value' => (string) $especialidad->id, 'label' => $especialidad->nombre])->values()->all()"
                    :value="old('especialidad_id', $usuario->especialidad_id)"
                    placeholder="Consulta interna o externa"
                    empty-label="N/A"
                />
            </div>
            <div class="col-md-6">
                <label class="form-label">Colegiatura</label>
                <input class="form-control" name="colegiatura" value="{{ old('colegiatura', $usuario->colegiatura) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-telephone me-1"></i>Telefono institucional</label>
                <input class="form-control" name="telefono_institucional" value="{{ old('telefono_institucional', $usuario->telefono_institucional) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Celular</label>
                <input class="form-control" name="celular" value="{{ old('celular', $usuario->celular) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Observaciones administrativas</label>
                <textarea class="form-control" name="observaciones_admin" rows="3">{{ old('observaciones_admin', $usuario->observaciones_admin) }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('usuarios.index') }}"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
        <button class="btn btn-success" type="submit"><i class="bi bi-check2-circle me-1"></i>Guardar</button>
    </div>
</form>
@endsection

@extends('layouts.app')

@section('title', $usuario->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>{{ $usuario->nombre }}</h1>
        <p class="text-secondary mb-0"><i class="bi bi-envelope me-1"></i>{{ $usuario->email }}</p>
    </div>
    <div class="d-flex gap-2">
        @if (auth()->user()->canModule('usuarios', 'edit'))
            <a class="btn btn-outline-primary" href="{{ route('usuarios.edit', $usuario) }}"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endif
        @if (auth()->user()->canModule('usuarios', 'delete') && auth()->id() !== $usuario->id)
            <form method="post" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('Eliminar este usuario?')">
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
            <dt class="col-sm-3"><i class="bi bi-shield-lock me-1"></i>Rol</dt>
            <dd class="col-sm-9"><i class="bi {{ $usuario->rol_icon }} me-1"></i>{{ $usuario->rol_label }}</dd>
            <dt class="col-sm-3"><i class="bi bi-toggle-on me-1"></i>Estado</dt>
            <dd class="col-sm-9">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-person-vcard me-1"></i>DNI</dt>
            <dd class="col-sm-9">{{ $usuario->dni ?: 'N/A' }}</dd>
            <dt class="col-sm-3">No. empleado</dt>
            <dd class="col-sm-9">{{ $usuario->numero_empleado ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Cargo</dt>
            <dd class="col-sm-9">{{ $usuario->cargo ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Area</dt>
            <dd class="col-sm-9">{{ $usuario->area_departamento ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Unidad asignada</dt>
            <dd class="col-sm-9">{{ $usuario->unidad_asignada ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Turno</dt>
            <dd class="col-sm-9">{{ $usuario->turno ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Fecha de ingreso</dt>
            <dd class="col-sm-9">{{ $usuario->fecha_ingreso?->format('d/m/Y') ?? 'N/A' }}</dd>
            <dt class="col-sm-3">Servicio asignado</dt>
            <dd class="col-sm-9">{{ $usuario->especialidad?->nombre ?? 'N/A' }}</dd>
            <dt class="col-sm-3">Colegiatura</dt>
            <dd class="col-sm-9">{{ $usuario->colegiatura ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Telefono institucional</dt>
            <dd class="col-sm-9">{{ $usuario->telefono_institucional ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Celular</dt>
            <dd class="col-sm-9">{{ $usuario->celular ?: 'N/A' }}</dd>
            <dt class="col-sm-3">Observaciones</dt>
            <dd class="col-sm-9">{{ $usuario->observaciones_admin ?: 'N/A' }}</dd>
        </dl>
    </div>
</div>
@endsection

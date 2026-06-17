@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><i class="bi bi-person-gear me-2 text-primary"></i>Usuarios y roles</h1>
    @if (auth()->user()->canModule('usuarios', 'create'))
        <a class="btn btn-success" href="{{ route('usuarios.create') }}"><i class="bi bi-person-plus me-1"></i>Nuevo usuario</a>
    @endif
</div>

<form class="row g-2 mb-3" method="get">
    <div class="col-md-8">
        <input class="form-control" name="q" value="{{ $q }}" placeholder="Buscar por nombre, correo o DNI">
    </div>
    <div class="col-md-3">
        <select class="form-select" name="rol">
            <option value="">Todos los roles</option>
            @foreach ($roles as $item)
                <option value="{{ $item }}" @selected($rol === $item)>{{ \App\Models\Usuario::roleLabel($item) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-1 d-grid">
        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th><i class="bi bi-person me-1"></i>Usuario</th>
                    <th><i class="bi bi-shield-lock me-1"></i>Rol</th>
                    <th><i class="bi bi-toggle-on me-1"></i>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>
                            <strong>{{ $usuario->nombre }}</strong>
                            <div class="small text-secondary text-break"><i class="bi bi-envelope me-1"></i>{{ $usuario->email }}</div>
                            <div class="small text-secondary">{{ $usuario->cargo ?: 'Sin cargo' }}{{ $usuario->area_departamento ? ' - '.$usuario->area_departamento : '' }}</div>
                        </td>
                        <td><span class="badge text-bg-dark"><i class="bi {{ $usuario->rol_icon }} me-1"></i>{{ $usuario->rol_label }}</span></td>
                        <td>
                            <span class="badge {{ $usuario->activo ? 'text-bg-success' : 'text-bg-secondary' }}">
                                <i class="bi {{ $usuario->activo ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                            <div class="small text-secondary">{{ $usuario->numero_empleado ?: ($usuario->especialidad?->nombre ?? 'N/A') }}</div>
                        </td>
                        <td class="text-end text-nowrap">
                            @if (auth()->user()->canModule('usuarios', 'view'))
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('usuarios.show', $usuario) }}" title="Ver"><i class="bi bi-eye"></i></a>
                            @endif
                            @if (auth()->user()->canModule('usuarios', 'edit'))
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('usuarios.edit', $usuario) }}" title="Editar"><i class="bi bi-pencil"></i></a>
                            @endif
                            @if (auth()->user()->canModule('usuarios', 'delete') && auth()->id() !== $usuario->id)
                                <form class="d-inline" method="post" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('Eliminar este usuario?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">Sin usuarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $usuarios->links() }}</div>
@endsection

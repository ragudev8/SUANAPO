@extends('layouts.app')

@section('title', 'Matriz de permisos')

@section('content')
@php
    $impersonator = session('impersonator_id') ? \App\Models\Usuario::find(session('impersonator_id')) : null;
    $canEditPermissions = auth()->user()->rol === 'super_admin' || $impersonator?->rol === 'super_admin';
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-shield-lock me-2 text-primary"></i>Matriz de permisos</h1>
        <p class="text-secondary mb-0">Permisos por rol y modulo del sistema.</p>
    </div>
    @if ($canEditPermissions)
        <button class="btn btn-success" form="permissionsForm">
            <i class="bi bi-floppy me-1"></i>Guardar permisos
        </button>
    @else
        <span class="badge text-bg-light">
            <i class="bi bi-eye me-1"></i>Solo lectura
        </span>
    @endif
</div>

@if (! $canEditPermissions)
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i>Solo el super admin puede modificar permisos.
    </div>
@endif

<form id="permissionsForm" method="post" action="{{ route('permisos.update') }}">
    @csrf
    @method('put')

    @foreach ($permissions as $role => $rolePermissions)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                <h2 class="h5 mb-0 text-capitalize">
                    <i class="bi {{ \App\Models\Usuario::roleIcon($role) }} me-1"></i>{{ \App\Models\Usuario::roleLabel($role) }}
                </h2>
                @if ($role === 'super_admin')
                    <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Acceso total fijo</span>
                @else
                    <span class="badge text-bg-dark">{{ count($modules) }} modulos</span>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 permissions-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-grid-3x3-gap me-1"></i>Modulo</th>
                            @foreach ($actions as $action)
                                <th class="text-center">{{ ucfirst($action) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if ($role === 'super_admin')
                            <tr>
                                <td>Todos los modulos</td>
                                @foreach ($actions as $action)
                                    <td class="text-center">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    </td>
                                @endforeach
                            </tr>
                        @else
                            @foreach ($modules as $module => $label)
                                <tr>
                                    <td>
                                        <strong>{{ $label }}</strong>
                                        <div class="small text-secondary">{{ $module }}</div>
                                    </td>
                                    @foreach ($actions as $action)
                                        @php($checked = in_array($action, $rolePermissions[$module] ?? [], true))
                                        <td class="text-center">
                                            @if ($canEditPermissions)
                                                <input
                                                    class="form-check-input permission-check"
                                                    type="checkbox"
                                                    name="permissions[{{ $role }}][{{ $module }}][]"
                                                    value="{{ $action }}"
                                                    @checked($checked)
                                                    aria-label="{{ $role }} {{ $label }} {{ $action }}"
                                                >
                                            @else
                                                @if ($checked)
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @else
                                                    <i class="bi bi-dash-circle text-secondary"></i>
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</form>
@endsection

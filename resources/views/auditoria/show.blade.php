@extends('layouts.app')

@section('title', 'Log auditoria')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-clipboard-data me-2 text-primary"></i>Log #{{ $log->id }}</h1>
        <p class="text-secondary mb-0"><i class="bi bi-clock me-1"></i>{{ optional($log->created_at)->format('d/m/Y H:i') }} - <i class="bi bi-person me-1"></i>{{ $log->usuario?->nombre ?? 'Sistema' }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('auditoria.index') }}"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <h2 class="h5"><i class="bi bi-info-circle me-1 text-primary"></i>Detalle</h2>
            <dl class="row mb-0">
                <dt class="col-4">Accion</dt><dd class="col-8">{{ $log->accion }}</dd>
                <dt class="col-4">Tabla</dt><dd class="col-8">{{ $log->tabla_accedida ?: 'N/D' }}</dd>
                <dt class="col-4">Registro</dt><dd class="col-8">{{ $log->registro_id ?: 'N/D' }}</dd>
                <dt class="col-4">IP</dt><dd class="col-8">{{ $log->ip_address ?: 'N/D' }}</dd>
                <dt class="col-4">Agente</dt><dd class="col-8 text-break">{{ $log->user_agent ?: 'N/D' }}</dd>
            </dl>
        </div></div>
    </div>
    @if (auth()->user()->canModule('auditoria', 'edit'))
    <div class="col-lg-7">
        <form class="card border-0 shadow-sm" method="post" action="{{ route('auditoria.update', $log) }}">
            @csrf @method('put')
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-pencil-square me-1 text-primary"></i>Editar log</h2>
                <div class="row g-2">
                    <div class="col-md-4"><label class="form-label">Accion</label><select class="form-select" name="accion">@foreach(['view','create','update','delete','login','logout','download','print','sign','modify'] as $v)<option @selected($log->accion===$v)>{{ $v }}</option>@endforeach</select></div>
                    <div class="col-md-4"><label class="form-label">Tabla</label><input class="form-control" name="tabla_accedida" value="{{ $log->tabla_accedida }}"></div>
                    <div class="col-md-4"><label class="form-label">Registro</label><input class="form-control" type="number" name="registro_id" value="{{ $log->registro_id }}"></div>
                    <div class="col-md-4"><label class="form-label">Usuario</label><input class="form-control" name="usuario_id" value="{{ $log->usuario_id }}"></div>
                    <div class="col-md-4"><label class="form-label">IP</label><input class="form-control" name="ip_address" value="{{ $log->ip_address }}"></div>
                    <div class="col-md-4"><label class="form-label">Agente</label><input class="form-control" name="user_agent" value="{{ $log->user_agent }}"></div>
                </div>
            </div>
            <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button></div>
        </form>
    </div>
    @endif
</div>
@endsection

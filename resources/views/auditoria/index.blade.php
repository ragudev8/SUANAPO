@extends('layouts.app')

@section('title', 'Auditoria')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-clipboard-data me-2 text-primary"></i>Auditoria</h1>
        <p class="text-secondary mb-0">Logs de acceso, acciones y cambios del sistema.</p>
    </div>
    @if (auth()->user()->esAdmin())
        <a class="btn btn-outline-primary" href="{{ route('auditoria.export') }}">
            <i class="bi bi-download me-1"></i>Descargar Excel
        </a>
    @endif
</div>

@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

@if (auth()->user()->canModule('auditoria', 'create'))
<form class="card border-0 shadow-sm mb-3" method="post" action="{{ route('auditoria.store') }}">
    @csrf
    <div class="card-body">
        <h2 class="h5"><i class="bi bi-plus-circle me-1 text-primary"></i>Registrar log manual</h2>
        <div class="row g-2">
            <div class="col-md-3"><label class="form-label"><i class="bi bi-person me-1"></i>Usuario</label><select class="form-select" name="usuario_id"><option value="">Sistema</option>@foreach($usuarios as $u)<option value="{{ $u->id }}">{{ $u->nombre }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label"><i class="bi bi-lightning me-1"></i>Accion</label><select class="form-select" name="accion">@foreach(['view','create','update','delete','login','logout','download','print','sign','modify'] as $v)<option>{{ $v }}</option>@endforeach</select></div>
            <div class="col-md-3"><label class="form-label"><i class="bi bi-table me-1"></i>Tabla</label><input class="form-control" name="tabla_accedida"></div>
            <div class="col-md-2"><label class="form-label">Registro</label><input class="form-control" type="number" name="registro_id" min="1"></div>
            <div class="col-md-2"><label class="form-label">IP</label><input class="form-control" name="ip_address" value="127.0.0.1"></div>
            <div class="col-12"><label class="form-label">User agent</label><input class="form-control" name="user_agent" value="Registro manual"></div>
        </div>
    </div>
    <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button></div>
</form>
@endif

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th><i class="bi bi-clock-history me-1"></i>Registro</th><th><i class="bi bi-lightning me-1"></i>Accion</th><th><i class="bi bi-hdd-network me-1"></i>Origen</th><th></th></tr></thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>
                            <strong>{{ $log->usuario?->nombre ?? 'Sistema' }}</strong>
                            <div class="small text-secondary"><i class="bi bi-calendar-event me-1"></i>{{ optional($log->created_at)->format('d/m/Y H:i') }}</div>
                        </td>
                        <td><span class="badge text-bg-light">{{ $log->accion }}</span></td>
                        <td>
                            {{ $log->tabla_accedida ?: 'N/D' }} @if($log->registro_id)<span class="text-secondary">#{{ $log->registro_id }}</span>@endif
                            <div class="small text-secondary">{{ $log->ip_address ?: 'N/D' }}</div>
                        </td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('auditoria.show', $log) }}"><i class="bi bi-eye"></i></a>
                            @if (auth()->user()->canModule('auditoria', 'delete'))
                                <form class="d-inline" method="post" action="{{ route('auditoria.destroy', $log) }}" onsubmit="return confirm('Eliminar log?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection

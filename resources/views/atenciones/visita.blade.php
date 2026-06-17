@extends('layouts.app')

@section('title', 'Visita')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-person-check me-2 text-primary"></i>Visita #{{ $visita->numero_orden }}</h1>
        <p class="text-secondary mb-0"><i class="bi bi-calendar-event me-1"></i>{{ $visita->fecha_visita->format('d/m/Y') }} &middot; <i class="bi bi-clock me-1"></i>{{ $visita->hora_llegada }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if (auth()->user()->canModule('atenciones', 'edit'))
            <a class="btn btn-outline-primary" href="{{ route('atenciones.visitas.edit', $visita) }}"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endif
        @if (auth()->user()->canModule('atenciones', 'delete'))
            <form method="post" action="{{ route('atenciones.visitas.destroy', $visita) }}" onsubmit="return confirm('Eliminar esta visita?')">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Eliminar</button>
            </form>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3"><i class="bi bi-person me-1"></i>Paciente</dt><dd class="col-sm-9">{{ $visita->paciente->nombre }}</dd>
            <dt class="col-sm-3"><i class="bi bi-person-vcard me-1"></i>DNI</dt><dd class="col-sm-9">{{ $visita->paciente->dni }}</dd>
            <dt class="col-sm-3"><i class="bi bi-activity me-1"></i>Estado</dt><dd class="col-sm-9">{{ str_replace('_', ' ', $visita->estado) }}</dd>
            <dt class="col-sm-3"><i class="bi bi-arrow-left-right me-1"></i>Tipo de consulta</dt><dd class="col-sm-9">{{ ['sin_asignar' => 'Sin asignar', 'interna' => 'Interna', 'externa' => 'Externa'][$visita->cita?->tipo_consulta ?? 'sin_asignar'] ?? 'Sin asignar' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-hospital me-1"></i>A que viene</dt><dd class="col-sm-9">{{ $visita->cita?->especialidad?->nombre ?? 'Sin asignar' }}</dd>
        </dl>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h5 mb-0"><i class="bi bi-heart-pulse me-1 text-danger"></i>Preclinica</h2>
                @if(auth()->user()->canModule('atenciones','edit'))<a class="btn btn-sm btn-outline-primary" href="{{ route('atenciones.preclinica', $visita) }}"><i class="bi bi-box-arrow-up-right me-1"></i>Abrir</a>@endif
            </div>
            @if($visita->cita?->preclinica)
                <div>{{ $visita->cita->preclinica->presion_sistolica ?: 'N/D' }}/{{ $visita->cita->preclinica->presion_diastolica ?: 'N/D' }} mmHg</div>
                <div class="text-secondary small">Pulso {{ $visita->cita->preclinica->pulso ?: 'N/D' }} - Temp {{ $visita->cita->preclinica->temperatura ?: 'N/D' }}</div>
                <p class="text-secondary mb-0 mt-2">{{ $visita->cita->preclinica->notas_iniciales }}</p>
            @else
                <p class="text-secondary mb-0">Pendiente.</p>
            @endif
        </div></div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h5 mb-0"><i class="bi bi-clipboard2-pulse me-1 text-primary"></i>Consulta medica</h2>
                @if(auth()->user()->canModule('atenciones','edit'))<a class="btn btn-sm btn-outline-primary" href="{{ route('atenciones.consulta', $visita) }}"><i class="bi bi-box-arrow-up-right me-1"></i>Abrir</a>@endif
            </div>
            @if($visita->cita?->consulta)
                <strong>{{ $visita->cita->consulta->medico?->nombre }}</strong>
                <p class="text-secondary mb-1">{{ $visita->cita->consulta->sintomas }}</p>
                <p class="mb-0">{{ $visita->cita->consulta->tratamiento_prescrito ?: 'Sin tratamiento registrado.' }}</p>
            @else
                <p class="text-secondary mb-0">Pendiente.</p>
            @endif
        </div></div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h5 mb-0"><i class="bi bi-capsule me-1 text-success"></i>Farmacia y cierre</h2>
                @if(auth()->user()->canModule('atenciones','edit'))<a class="btn btn-sm btn-outline-primary" href="{{ route('atenciones.dispensacion', $visita) }}"><i class="bi bi-box-arrow-up-right me-1"></i>Abrir</a>@endif
            </div>
            <p class="text-secondary mb-2">Estado: {{ str_replace('_', ' ', $visita->estado) }}</p>
            @if(auth()->user()->canModule('atenciones','edit') && $visita->estado !== 'finalizado')
                <form method="post" action="{{ route('atenciones.cerrar', $visita) }}" onsubmit="return confirm('Finalizar esta atencion?')">
                    @csrf
                    <button class="btn btn-sm btn-success"><i class="bi bi-check2-circle me-1"></i>Finalizar atencion</button>
                </form>
            @endif
        </div></div>
    </div>
</div>
@endsection

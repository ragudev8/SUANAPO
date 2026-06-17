@extends('layouts.app')

@section('title', 'Board de atenciones')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="bi bi-kanban me-2 text-primary"></i>Board de atenciones</h1>
    @if (auth()->user()->canModule('atenciones', 'create'))
        <a class="btn btn-primary" href="{{ route('atenciones.llegada.create') }}"><i class="bi bi-person-plus me-1"></i>Llegada</a>
    @endif
</div>

<div class="row g-3">
    @forelse ($visitas as $visita)
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between">
                    <strong><i class="bi bi-hash me-1"></i>{{ $visita->numero_orden }} {{ $visita->paciente->nombre }}</strong>
                    <span class="badge text-bg-info status-pill"><i class="bi bi-activity me-1"></i>{{ str_replace('_', ' ', $visita->estado) }}</span>
                </div>
                <div class="card-body">
                    <div class="small text-secondary"><i class="bi bi-person-vcard me-1"></i>DNI</div>
                    <div>{{ $visita->paciente->dni }}</div>
                    <div class="small text-secondary mt-2"><i class="bi bi-arrow-left-right me-1"></i>Tipo de consulta</div>
                    <div>{{ ['sin_asignar' => 'Sin asignar', 'interna' => 'Interna', 'externa' => 'Externa'][$visita->cita?->tipo_consulta ?? 'sin_asignar'] ?? 'Sin asignar' }}</div>
                    <div class="small text-secondary mt-2"><i class="bi bi-hospital me-1"></i>A que viene</div>
                    <div>{{ $visita->cita?->especialidad?->nombre ?? 'Sin asignar' }}</div>
                    <div class="small text-secondary mt-2"><i class="bi bi-list-check me-1"></i>Avance</div>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge {{ $visita->cita?->preclinica ? 'text-bg-success' : 'text-bg-light' }}">Preclinica</span>
                        <span class="badge {{ $visita->cita?->consulta ? 'text-bg-success' : 'text-bg-light' }}">Consulta</span>
                        <span class="badge {{ $visita->estado === 'finalizado' ? 'text-bg-success' : 'text-bg-light' }}">Cierre</span>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex flex-wrap gap-2">
                    @if (auth()->user()->canModule('atenciones', 'view'))
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('atenciones.visitas.show', $visita) }}" title="Ver"><i class="bi bi-eye"></i></a>
                    @endif
                    @if (auth()->user()->canModule('atenciones', 'edit') && $visita->estado !== 'finalizado')
                        @if (! $visita->cita?->preclinica)
                            <a class="btn btn-sm btn-success" href="{{ route('atenciones.preclinica', $visita) }}"><i class="bi bi-heart-pulse me-1"></i>Preclinica</a>
                        @elseif (! $visita->cita?->consulta)
                            <a class="btn btn-sm btn-success" href="{{ route('atenciones.consulta', $visita) }}"><i class="bi bi-clipboard2-pulse me-1"></i>Consulta</a>
                        @else
                            <a class="btn btn-sm btn-outline-success" href="{{ route('atenciones.dispensacion', $visita) }}"><i class="bi bi-capsule me-1"></i>Dispensar</a>
                            <form method="post" action="{{ route('atenciones.cerrar', $visita) }}" onsubmit="return confirm('Finalizar esta atencion?')">
                                @csrf
                                <button class="btn btn-sm btn-success"><i class="bi bi-check2-circle me-1"></i>Cerrar</button>
                            </form>
                        @endif
                    @endif
                    @if (auth()->user()->canModule('atenciones', 'edit'))
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('atenciones.visitas.edit', $visita) }}" title="Editar"><i class="bi bi-pencil"></i></a>
                    @endif
                    @if (auth()->user()->canModule('atenciones', 'delete'))
                        <form method="post" action="{{ route('atenciones.visitas.destroy', $visita) }}" onsubmit="return confirm('Eliminar esta visita?')">
                            @csrf
                            @method('delete')
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    @endif
                    @if (auth()->user()->canModule('pacientes', 'view'))
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('pacientes.show', $visita->paciente) }}"><i class="bi bi-folder2-open me-1"></i>Expediente</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border">No hay llegadas registradas hoy.</div>
        </div>
    @endforelse
</div>
@endsection

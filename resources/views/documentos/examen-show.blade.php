@extends('layouts.app')

@section('title', 'Detalle de examen')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-clipboard2-pulse me-2 text-primary"></i>Detalle de examen</h1>
        <p class="text-secondary mb-0">{{ $examen->paciente?->nombre ?? 'Paciente N/A' }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('documentos.index') }}"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3"><i class="bi bi-person me-1"></i>Paciente</dt>
            <dd class="col-sm-9">{{ $examen->paciente?->nombre ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-tag me-1"></i>Tipo</dt>
            <dd class="col-sm-9">{{ ucfirst($examen->tipo) }}</dd>
            <dt class="col-sm-3"><i class="bi bi-calendar-event me-1"></i>Fecha</dt>
            <dd class="col-sm-9">{{ $examen->fecha_examen?->format('d/m/Y') ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-check-circle me-1"></i>Estado</dt>
            <dd class="col-sm-9">{{ $examen->aprobado ? 'Aprobado' : 'Pendiente' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-person-badge me-1"></i>Aprobador</dt>
            <dd class="col-sm-9">{{ $examen->medicoAprobador?->nombre ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-droplet me-1"></i>Resultados sangre</dt>
            <dd class="col-sm-9" style="white-space: pre-line;">{{ $examen->resultados_sangre ?: 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-activity me-1"></i>Pruebas</dt>
            <dd class="col-sm-9">
                <span class="badge {{ $examen->cardiograma ? 'text-bg-success' : 'text-bg-secondary' }}">Cardiograma</span>
                <span class="badge {{ $examen->ultrasonido_abdominal ? 'text-bg-success' : 'text-bg-secondary' }}">Ultrasonido</span>
                <span class="badge {{ $examen->rayos_x_torax ? 'text-bg-success' : 'text-bg-secondary' }}">Rayos X torax</span>
                <span class="badge {{ $examen->rayos_x_lumbar ? 'text-bg-success' : 'text-bg-secondary' }}">Rayos X lumbar</span>
            </dd>
            <dt class="col-sm-3"><i class="bi bi-card-text me-1"></i>Notas medicas</dt>
            <dd class="col-sm-9" style="white-space: pre-line;">{{ $examen->notas_medicas ?: 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-filetype-pdf me-1"></i>Archivo PDF</dt>
            <dd class="col-sm-9">{{ $examen->pdf_ruta ?: 'N/A' }}</dd>
        </dl>
    </div>
</div>
@endsection

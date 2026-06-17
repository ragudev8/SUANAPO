@extends('layouts.app')

@section('title', 'Detalle de incapacidad')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-calendar2-x me-2 text-danger"></i>Detalle de incapacidad</h1>
        <p class="text-secondary mb-0">{{ $incapacidad->paciente?->nombre ?? 'Paciente N/A' }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('documentos.index') }}"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3"><i class="bi bi-person me-1"></i>Paciente</dt>
            <dd class="col-sm-9">{{ $incapacidad->paciente?->nombre ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-person-badge me-1"></i>Medico</dt>
            <dd class="col-sm-9">{{ $incapacidad->medico?->nombre ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-calendar-event me-1"></i>Periodo</dt>
            <dd class="col-sm-9">{{ $incapacidad->fecha_inicio?->format('d/m/Y') ?? 'N/A' }} al {{ $incapacidad->fecha_fin?->format('d/m/Y') ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-hourglass-split me-1"></i>Dias de reposo</dt>
            <dd class="col-sm-9">{{ $incapacidad->dias_reposo }} dias</dd>
            <dt class="col-sm-3"><i class="bi bi-house-heart me-1"></i>Lugar de reposo</dt>
            <dd class="col-sm-9">{{ ucfirst($incapacidad->lugar_reposo ?? 'casa') }}</dd>
            <dt class="col-sm-3"><i class="bi bi-card-text me-1"></i>Motivo</dt>
            <dd class="col-sm-9" style="white-space: pre-line;">{{ $incapacidad->motivo }}</dd>
            <dt class="col-sm-3"><i class="bi bi-pen me-1"></i>Firma jefe medico</dt>
            <dd class="col-sm-9">{{ $incapacidad->firma_jefe_medico_digital ?: 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-award me-1"></i>Sello clinica</dt>
            <dd class="col-sm-9">{{ $incapacidad->sello_clinica ?: 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-filetype-pdf me-1"></i>Archivo PDF</dt>
            <dd class="col-sm-9">{{ $incapacidad->pdf_ruta ?: 'N/A' }}</dd>
        </dl>
    </div>
</div>
@endsection

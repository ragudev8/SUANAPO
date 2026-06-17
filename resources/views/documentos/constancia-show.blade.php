@extends('layouts.app')

@section('title', 'Detalle de constancia')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-file-earmark-check me-2 text-success"></i>Detalle de constancia</h1>
        <p class="text-secondary mb-0">{{ $constancia->paciente?->nombre ?? 'Paciente N/A' }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('documentos.index') }}"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3"><i class="bi bi-person me-1"></i>Paciente</dt>
            <dd class="col-sm-9">{{ $constancia->paciente?->nombre ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-person-badge me-1"></i>Medico</dt>
            <dd class="col-sm-9">{{ $constancia->medico?->nombre ?? 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-tag me-1"></i>Tipo</dt>
            <dd class="col-sm-9">{{ ucfirst($constancia->tipo) }}</dd>
            <dt class="col-sm-3"><i class="bi bi-lightbulb me-1"></i>Asunto</dt>
            <dd class="col-sm-9">{{ $constancia->asunto ?: 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-card-text me-1"></i>Contenido</dt>
            <dd class="col-sm-9" style="white-space: pre-line;">{{ $constancia->contenido }}</dd>
            <dt class="col-sm-3"><i class="bi bi-pen me-1"></i>Firma medico</dt>
            <dd class="col-sm-9">{{ $constancia->firma_medico_digital ?: 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-award me-1"></i>Sello clinica</dt>
            <dd class="col-sm-9">{{ $constancia->sello_clinica ?: 'N/A' }}</dd>
            <dt class="col-sm-3"><i class="bi bi-filetype-pdf me-1"></i>Archivo PDF</dt>
            <dd class="col-sm-9">{{ $constancia->pdf_ruta ?: 'N/A' }}</dd>
        </dl>
    </div>
</div>
@endsection

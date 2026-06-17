@extends('layouts.app')

@section('title', $paciente->nombre)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-person-vcard me-2 text-primary"></i>{{ $paciente->nombre }}</h1>
        <p class="text-secondary mb-0">
            <i class="bi bi-person-vcard me-1"></i>DNI {{ $paciente->dni }} &middot; <i class="bi {{ $paciente->vinculo_institucional_icon }} me-1"></i>{{ $paciente->vinculo_institucional_label }} &middot; <i class="bi bi-shield-check me-1"></i>{{ $paciente->tipo_paciente_label }}
            @if ($paciente->edad)
                &middot; {{ $paciente->edad }} anos
            @endif
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#detallesPaciente" aria-expanded="false" aria-controls="detallesPaciente">
            <i class="bi bi-card-list me-1"></i>Ver mas detalles
        </button>
        @if (auth()->user()->canModule('pacientes', 'edit'))
            <a class="btn btn-outline-primary" href="{{ route('pacientes.edit', $paciente) }}"><i class="bi bi-pencil"></i></a>
        @endif
        @if (auth()->user()->canModule('pacientes', 'delete'))
            <form method="post" action="{{ route('pacientes.destroy', $paciente) }}" onsubmit="return confirm('Eliminar este paciente?')">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
        @endif
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <h2 class="h5"><i class="bi bi-info-circle me-1 text-primary"></i>Resumen</h2>
            <dl class="row mb-0">
                <dt class="col-5">Sexo</dt><dd class="col-7">{{ $paciente->sexo ?: 'N/D' }}</dd>
                <dt class="col-5">Edad</dt><dd class="col-7">{{ $paciente->edad ? $paciente->edad.' anos' : 'N/D' }}</dd>
                <dt class="col-5">Vinculo</dt><dd class="col-7"><i class="bi {{ $paciente->vinculo_institucional_icon }} me-1"></i>{{ $paciente->vinculo_institucional_label }}</dd>
                <dt class="col-5">Tipo</dt><dd class="col-7">{{ $paciente->tipo_paciente_label }}</dd>
            </dl>
        </div></div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <h2 class="h5"><i class="bi bi-telephone me-1 text-primary"></i>Contacto principal</h2>
            <dl class="row mb-0">
                <dt class="col-5">Celular</dt><dd class="col-7">{{ $paciente->celular ?: 'N/D' }}</dd>
                <dt class="col-5">Telefono</dt><dd class="col-7">{{ $paciente->telefono ?: 'N/D' }}</dd>
                <dt class="col-5">Correo</dt><dd class="col-7 text-break">{{ $paciente->correo ?: 'N/D' }}</dd>
            </dl>
        </div></div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <h2 class="h5"><i class="bi bi-heart-pulse me-1 text-danger"></i>Salud base</h2>
            <dl class="mb-0">
                <dt>Alergias</dt><dd>{{ $paciente->alergias ?: 'Sin registro' }}</dd>
                <dt>Observaciones</dt><dd>{{ $paciente->observaciones ?: 'Sin registro' }}</dd>
            </dl>
        </div></div>
    </div>
</div>

<div class="collapse mb-3" id="detallesPaciente">
    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <h2 class="h5"><i class="bi bi-person-lines-fill me-1 text-primary"></i>Datos personales</h2>
                <dl class="row mb-0">
                    <dt class="col-5">Nacimiento</dt><dd class="col-7">{{ optional($paciente->fecha_nacimiento)->format('d/m/Y') ?: 'N/D' }}</dd>
                    <dt class="col-5">Estado civil</dt><dd class="col-7">{{ $paciente->estado_civil ?: 'N/D' }}</dd>
                    <dt class="col-5">Usuario</dt>
                    <dd class="col-7">
                        @if ($paciente->usuario)
                            <i class="bi {{ $paciente->usuario->rol_icon }} me-1"></i>{{ $paciente->usuario->nombre }}
                            <div class="small text-secondary">{{ $paciente->usuario->rol_label }}</div>
                        @else
                            N/D
                        @endif
                    </dd>
                    <dt class="col-5">Direccion</dt><dd class="col-7">{{ $paciente->direccion ?: 'N/D' }}</dd>
                </dl>
            </div></div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <h2 class="h5"><i class="bi bi-shield-check me-1 text-primary"></i>ANAPO</h2>
                <dl class="row mb-0">
                    <dt class="col-5">Vinculo</dt><dd class="col-7">{{ $paciente->vinculo_institucional_label }}</dd>
                    <dt class="col-5">Tipo</dt><dd class="col-7">{{ $paciente->tipo_paciente_label }}</dd>
                    <dt class="col-5">Placa</dt><dd class="col-7">{{ $paciente->numero_placa ?: 'N/D' }}</dd>
                    <dt class="col-5">Cargo</dt><dd class="col-7">{{ $paciente->ocupacion ?: 'N/D' }}</dd>
                    <dt class="col-5">Unidad</dt><dd class="col-7">{{ $paciente->unidad_dependencia ?: 'N/D' }}</dd>
                </dl>
            </div></div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <h2 class="h5"><i class="bi bi-exclamation-triangle me-1 text-warning"></i>Emergencia</h2>
                <dl class="row mb-0">
                    <dt class="col-5">Contacto</dt><dd class="col-7">{{ $paciente->contacto_emergencia_nombre ?: 'N/D' }}</dd>
                    <dt class="col-5">Telefono</dt><dd class="col-7">{{ $paciente->contacto_emergencia_telefono ?: 'N/D' }}</dd>
                    <dt class="col-5">Responsable</dt><dd class="col-7">{{ $paciente->responsable_nombre ?: 'N/D' }}</dd>
                    <dt class="col-5">Parentesco</dt><dd class="col-7">{{ $paciente->responsable_parentesco ?: 'N/D' }}</dd>
                </dl>
            </div></div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <h2 class="h5"><i class="bi bi-journal-medical me-1 text-primary"></i>Antecedentes</h2>
                <div class="row g-3">
                    <div class="col-lg-4">
                        <strong>Familiares</strong>
                        <p class="text-secondary mb-0">{{ $paciente->expediente?->antecedentes_familiares ?: 'Sin registro' }}</p>
                    </div>
                    <div class="col-lg-4">
                        <strong>Personales</strong>
                        <p class="text-secondary mb-0">{{ $paciente->expediente?->antecedentes_personales ?: 'Sin registro' }}</p>
                    </div>
                    <div class="col-lg-4">
                        <strong>Quirurgicos</strong>
                        <p class="text-secondary mb-0">{{ $paciente->expediente?->antecedentes_quirurgicos ?: 'Sin registro' }}</p>
                    </div>
                </div>
            </div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-clipboard2-pulse me-1 text-primary"></i>Consultas</h2>
                @forelse ($paciente->consultas as $consulta)
                    <div class="border-bottom py-2">
                        <strong>{{ $consulta->created_at->format('d/m/Y') }}</strong>
                        <div class="text-secondary">{{ $consulta->notas_medicas }}</div>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin consultas registradas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-capsule me-1 text-success"></i>Dispensaciones</h2>
                @forelse ($paciente->recetas as $receta)
                    @foreach ($receta->detalles as $detalle)
                        <div class="border-bottom py-2">
                            <strong>{{ $detalle->medicamento?->nombre ?? 'Medicamento' }}</strong>
                            <div class="text-secondary">
                                {{ optional($detalle->fecha_dispensado)->format('d/m/Y') ?: optional($receta->fecha_emision)->format('d/m/Y') }}
                                · Cantidad {{ $detalle->cantidad_medicamento ?: 'N/D' }}
                                · Folio {{ $receta->folio_unico }}
                            </div>
                            @if (auth()->user()->canModule('recetas', 'view'))
                                <a class="small" href="{{ route('recetas.show', $receta) }}"><i class="bi bi-eye me-1"></i>Ver receta</a>
                            @endif
                        </div>
                    @endforeach
                @empty
                    <p class="text-secondary mb-0">Sin dispensaciones registradas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-0">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-person-check me-1 text-primary"></i>Atenciones</h2>
                @forelse ($paciente->libroVisitas->sortByDesc('fecha_visita')->take(8) as $visita)
                    <div class="border-bottom py-2">
                        <strong>{{ optional($visita->fecha_visita)->format('d/m/Y') }}</strong>
                        <div class="text-secondary">Orden #{{ $visita->numero_orden }} &middot; {{ str_replace('_', ' ', $visita->estado) }}</div>
                        @if (auth()->user()->canModule('atenciones', 'view'))
                            <a class="small" href="{{ route('atenciones.visitas.show', $visita) }}"><i class="bi bi-eye me-1"></i>Ver atencion</a>
                        @endif
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin atenciones registradas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-file-earmark-medical me-1 text-primary"></i>Documentos</h2>
                @forelse ($paciente->incapacidades->take(4) as $item)
                    <div class="border-bottom py-2">
                        <strong>Incapacidad</strong>
                        <div class="text-secondary">{{ optional($item->fecha_inicio)->format('d/m/Y') }} &middot; {{ $item->dias_reposo }} dias</div>
                    </div>
                @empty
                    <p class="text-secondary mb-2">Sin incapacidades.</p>
                @endforelse
                @foreach ($paciente->constancias->take(4) as $item)
                    <div class="border-bottom py-2">
                        <strong>Constancia {{ $item->tipo }}</strong>
                        <div class="text-secondary">{{ $item->asunto ?: 'Sin asunto' }}</div>
                    </div>
                @endforeach
                @foreach ($paciente->examenesMedicos->take(4) as $item)
                    <div class="border-bottom py-2">
                        <strong>Examen {{ $item->tipo }}</strong>
                        <div class="text-secondary">{{ optional($item->fecha_examen)->format('d/m/Y') }} &middot; {{ $item->aprobado ? 'Aprobado' : 'Pendiente' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-droplet-half me-1 text-danger"></i>Sangre</h2>
                @forelse ($paciente->solicitudesSangre->take(4) as $item)
                    <div class="border-bottom py-2">
                        <strong>Solicitud {{ $item->tipo_sangre }}</strong>
                        <div class="text-secondary">{{ $item->institucion ?: 'Sin hospital' }} &middot; {{ $item->estado }}</div>
                    </div>
                @empty
                    <p class="text-secondary mb-2">Sin solicitudes como paciente.</p>
                @endforelse
                @foreach ($paciente->solicitudesComoDonante->take(4) as $item)
                    <div class="border-bottom py-2">
                        <strong>Donante asignado</strong>
                        <div class="text-secondary">{{ $item->paciente?->nombre ?? $item->solicitante_nombre }} &middot; {{ $item->estado }}</div>
                    </div>
                @endforeach
                @foreach ($paciente->donacionesSangre->take(4) as $item)
                    <div class="border-bottom py-2">
                        <strong>Donacion confirmada</strong>
                        <div class="text-secondary">{{ optional($item->fecha_donacion)->format('d/m/Y') }} &middot; {{ $item->cantidad_unidades }} pinta(s)</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

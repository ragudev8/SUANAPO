@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-speedometer2 me-2 text-primary"></i>Panel operativo</h1>
        <p class="text-secondary mb-0"><i class="bi bi-calendar-event me-1"></i>{{ now()->format('d/m/Y') }}</p>
    </div>
    <a class="btn btn-primary" href="{{ route('atenciones.llegada.create') }}">
        <i class="bi bi-plus-circle me-1"></i>Registrar llegada
    </a>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="card metric border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-person-check me-1"></i>Visitas hoy</div><div class="display-6">{{ $visitasHoy }}</div><div class="small text-secondary">{{ $finalizadasHoy }} finalizadas</div></div></div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card metric border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-hourglass-split me-1"></i>En espera</div><div class="display-6">{{ $enEspera }}</div><div class="small text-secondary">Registrados o esperando medico</div></div></div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card metric border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-capsule me-1"></i>Farmacia</div><div class="display-6">{{ $enFarmacia }}</div><div class="small text-secondary">{{ $recetasActivas }} recetas activas, {{ $unidadesDispensadasMes }} unidades mes</div></div></div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card metric border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-exclamation-triangle me-1"></i>Alertas stock</div><div class="display-6">{{ $medicamentosStockBajo }}</div><div class="small text-secondary">{{ $medicamentosPorVencer }} por vencer</div></div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary"><i class="bi {{ auth()->user()->rol_icon }} me-1"></i>Tipo de usuario</div>
                <div class="h4 mb-1">{{ auth()->user()->rol_label }}</div>
                <div class="small text-secondary">
                    {{ auth()->user()->area_departamento ?: 'Area no asignada' }}
                    @if(auth()->user()->especialidad)
                        - {{ auth()->user()->especialidad->nombre }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-people me-1"></i>Total pacientes</div><div class="h2 mb-0">{{ $pacientes }}</div></div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-clipboard2-pulse me-1"></i>Consultas del mes</div><div class="h2 mb-0">{{ $consultasMes }}</div></div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-file-earmark-medical me-1"></i>Documentos mes</div><div class="h2 mb-0">{{ $documentosMes }}</div><div class="small text-secondary">{{ $incapacidadesMes }} incapacidades, {{ $constanciasMes }} constancias, {{ $examenesMes }} examenes</div></div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary"><i class="bi bi-droplet-half me-1"></i>Sangre pendiente</div><div class="h2 mb-0">{{ $sangrePendiente }}</div><div class="small text-secondary">{{ $examenesPendientes }} examenes pendientes</div></div></div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <div>
                <h2 class="h5 mb-1"><i class="bi bi-house-heart me-1 text-primary"></i>Reposos activos</h2>
                <p class="text-secondary mb-0 small">Personal con reposo vigente hoy: casa, cuadra o clinica.</p>
            </div>
            <span class="badge text-bg-light">
                <i class="bi bi-clipboard2-pulse me-1"></i>{{ $repososActivosTotal }} activo{{ $repososActivosTotal === 1 ? '' : 's' }}
            </span>
        </div>

        @forelse ($repososActivos as $reposo)
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 border-top py-3">
                <div>
                    <strong>{{ $reposo->paciente?->nombre ?? 'Paciente N/D' }}</strong>
                    <div class="small text-secondary">
                        <i class="bi bi-person-vcard me-1"></i>DNI {{ $reposo->paciente?->dni ?? 'N/D' }}
                    </div>
                </div>
                <div>
                    <span class="badge text-bg-light">
                        <i class="bi bi-house-heart me-1"></i>{{ ucfirst($reposo->lugar_reposo ?? 'casa') }}
                    </span>
                    <div class="small text-secondary mt-1">
                        <i class="bi bi-calendar-range me-1"></i>{{ $reposo->fecha_inicio?->format('d/m/Y') }} al {{ $reposo->fecha_fin?->format('d/m/Y') }}
                    </div>
                </div>
                <div class="reposo-motivo">
                    <div class="small text-secondary"><i class="bi bi-card-text me-1"></i>Motivo</div>
                    <div>{{ $reposo->motivo }}</div>
                </div>
                <div class="text-lg-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('documentos.incapacidades.show', $reposo) }}" title="Ver reposo">
                        <i class="bi bi-eye me-1"></i>Ver
                    </a>
                </div>
            </div>
        @empty
            <p class="text-secondary mb-0 border-top pt-3">No hay reposos activos para hoy.</p>
        @endforelse
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-diagram-3 me-1 text-primary"></i>Flujo de atencion de hoy</h2>
                <div class="row g-2">
                    @foreach ([
                        ['label' => 'Registrados', 'value' => $visitasPorEstado['registrado'] ?? 0, 'icon' => 'bi-person-plus'],
                        ['label' => 'Preclinica', 'value' => $enPreclinica, 'icon' => 'bi-heart-pulse'],
                        ['label' => 'Consulta', 'value' => $enConsulta, 'icon' => 'bi-clipboard2-pulse'],
                        ['label' => 'Farmacia', 'value' => $enFarmacia, 'icon' => 'bi-capsule'],
                        ['label' => 'Finalizados', 'value' => $finalizadasHoy, 'icon' => 'bi-check2-circle'],
                    ] as $step)
                        <div class="col-6 col-md">
                            <div class="border rounded p-2 h-100">
                                <div class="small text-secondary"><i class="bi {{ $step['icon'] }} me-1"></i>{{ $step['label'] }}</div>
                                <div class="h4 mb-0">{{ $step['value'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-clock-history me-1 text-primary"></i>Ultimas llegadas</h2>
                @forelse ($ultimasVisitas as $visita)
                    <div class="d-flex justify-content-between gap-2 border-bottom py-2">
                        <div>
                            <strong>{{ $visita->paciente?->nombre }}</strong>
                            <div class="small text-secondary"><i class="bi bi-hospital me-1"></i>{{ ['sin_asignar' => 'Sin asignar', 'interna' => 'Interna', 'externa' => 'Externa'][$visita->cita?->tipo_consulta ?? 'sin_asignar'] ?? 'Sin asignar' }} - {{ $visita->cita?->especialidad?->nombre ?? 'Sin servicio' }} - {{ str_replace('_', ' ', $visita->estado) }}</div>
                        </div>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('atenciones.visitas.show', $visita) }}" title="Ver"><i class="bi bi-eye"></i></a>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin llegadas registradas hoy.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-exclamation-triangle me-1 text-warning"></i>Medicamentos con stock bajo</h2>
                @forelse ($stockBajo as $medicamento)
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between gap-2">
                            <span>{{ $medicamento->nombre }}</span>
                            <strong>{{ $medicamento->cantidad_stock }} / min {{ $medicamento->cantidad_minima }}</strong>
                        </div>
                        <div class="small text-secondary">
                            <i class="bi bi-capsule me-1"></i>Dispensado mes {{ $medicamento->dispensado_mes }} - total {{ $medicamento->dispensado_total }}
                        </div>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin alertas de stock bajo.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-calendar2-x me-1 text-danger"></i>Medicamentos por vencer</h2>
                @forelse ($porVencer as $medicamento)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $medicamento->nombre }}</span>
                        <strong>{{ $medicamento->fecha_vencimiento?->format('d/m/Y') }}</strong>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Sin vencimientos próximos.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

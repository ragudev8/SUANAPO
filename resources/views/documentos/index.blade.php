@extends('layouts.app')

@section('title', 'Documentos')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-file-earmark-medical me-2 text-primary"></i>Documentos</h1>
        <p class="text-secondary mb-0">Incapacidades, constancias y examenes medicos.</p>
    </div>
</div>

@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

@if (auth()->user()->canModule('documentos', 'create'))
<div class="row g-3 mb-3">
    <div class="col-xl-4">
        <form class="card border-0 shadow-sm h-100" method="post" action="{{ route('documentos.incapacidades.store') }}">
            @csrf
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-calendar2-x me-1 text-danger"></i>Nueva incapacidad</h2>
                <div class="mb-2"><label class="form-label">Paciente</label><select class="form-select" name="paciente_id" required>@foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select></div>
                <div class="mb-2"><label class="form-label">Medico</label><select class="form-select" name="medico_id" required>@foreach($medicos as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach</select></div>
                <div class="row g-2">
                    <div class="col"><label class="form-label">Inicio</label><input class="form-control" type="date" name="fecha_inicio" value="{{ now()->toDateString() }}"></div>
                    <div class="col"><label class="form-label">Fin</label><input class="form-control" type="date" name="fecha_fin" value="{{ now()->addDays(2)->toDateString() }}"></div>
                    <div class="col"><label class="form-label">Dias</label><input class="form-control" type="number" name="dias_reposo" value="2" min="1"></div>
                </div>
                <div class="mt-2">
                    <label class="form-label"><i class="bi bi-house-heart me-1"></i>Lugar de reposo</label>
                    <select class="form-select" name="lugar_reposo" required>
                        <option value="casa">Casa</option>
                        <option value="cuadra">Cuadra</option>
                        <option value="clinica">Clinica</option>
                    </select>
                </div>
                <div class="mt-2"><label class="form-label">Motivo</label><textarea class="form-control" name="motivo" rows="2" required></textarea></div>
            </div>
            <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button></div>
        </form>
    </div>
    <div class="col-xl-4">
        <form class="card border-0 shadow-sm h-100" method="post" action="{{ route('documentos.constancias.store') }}">
            @csrf
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-file-earmark-check me-1 text-success"></i>Nueva constancia</h2>
                <div class="mb-2"><label class="form-label">Paciente</label><select class="form-select" name="paciente_id" required>@foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select></div>
                <div class="mb-2"><label class="form-label">Medico</label><select class="form-select" name="medico_id" required>@foreach($medicos as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach</select></div>
                <div class="row g-2"><div class="col"><label class="form-label">Tipo</label><select class="form-select" name="tipo"><option value="medica">Medica</option><option value="dictamen">Dictamen</option></select></div><div class="col"><label class="form-label">Asunto</label><input class="form-control" name="asunto"></div></div>
                <div class="mt-2"><label class="form-label">Contenido</label><textarea class="form-control" name="contenido" rows="2" required></textarea></div>
            </div>
            <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button></div>
        </form>
    </div>
    <div class="col-xl-4">
        <form class="card border-0 shadow-sm h-100" method="post" action="{{ route('documentos.examenes.store') }}">
            @csrf
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-clipboard2-pulse me-1 text-primary"></i>Nuevo examen</h2>
                <div class="mb-2"><label class="form-label">Paciente</label><select class="form-select" name="paciente_id" required>@foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select></div>
                <div class="row g-2"><div class="col"><label class="form-label">Tipo</label><select class="form-select" name="tipo"><option value="ingreso">Ingreso</option><option value="permanencia">Permanencia</option></select></div><div class="col"><label class="form-label">Fecha</label><input class="form-control" type="date" name="fecha_examen" value="{{ now()->toDateString() }}"></div></div>
                <div class="mt-2"><label class="form-label">Aprobador</label><select class="form-select" name="medico_aprobador_id"><option value="">N/D</option>@foreach($medicos as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach</select></div>
                <div class="mt-2"><label class="form-label">Notas</label><textarea class="form-control" name="notas_medicas" rows="2"></textarea></div>
                <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="aprobado" value="1" id="aprobado"><label class="form-check-label" for="aprobado">Aprobado</label></div>
            </div>
            <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button></div>
        </form>
    </div>
</div>
@endif

<div class="row g-3">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body"><h2 class="h5"><i class="bi bi-calendar2-x me-1 text-danger"></i>Incapacidades</h2></div>
            <div class="table-responsive"><table class="table table-hover mb-0"><tbody>
                @foreach($incapacidades as $item)
                    <tr><td><strong>{{ $item->paciente?->nombre }}</strong><div class="small text-secondary"><i class="bi bi-calendar-event me-1"></i>{{ optional($item->fecha_inicio)->format('d/m/Y') }} - {{ $item->dias_reposo }} dias</div><div class="small text-secondary"><i class="bi bi-house-heart me-1"></i>{{ ucfirst($item->lugar_reposo ?? 'casa') }}</div></td><td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="{{ route('documentos.incapacidades.show',$item) }}" title="Ver detalles"><i class="bi bi-eye"></i></a> @if(auth()->user()->canModule('documentos','delete'))<form class="d-inline" method="post" action="{{ route('documentos.incapacidades.destroy',$item) }}" onsubmit="return confirm('Eliminar incapacidad?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button></form>@endif</td></tr>
                @endforeach
            </tbody></table></div><div class="card-footer bg-white">{{ $incapacidades->links() }}</div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body"><h2 class="h5"><i class="bi bi-file-earmark-check me-1 text-success"></i>Constancias</h2></div>
            <div class="table-responsive"><table class="table table-hover mb-0"><tbody>
                @foreach($constancias as $item)
                    <tr><td><strong>{{ $item->paciente?->nombre }}</strong><div class="small text-secondary"><i class="bi bi-file-text me-1"></i>{{ ucfirst($item->tipo) }} - {{ $item->asunto ?: 'Sin asunto' }}</div></td><td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="{{ route('documentos.constancias.show',$item) }}" title="Ver detalles"><i class="bi bi-eye"></i></a> @if(auth()->user()->canModule('documentos','delete'))<form class="d-inline" method="post" action="{{ route('documentos.constancias.destroy',$item) }}" onsubmit="return confirm('Eliminar constancia?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button></form>@endif</td></tr>
                @endforeach
            </tbody></table></div><div class="card-footer bg-white">{{ $constancias->links() }}</div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body"><h2 class="h5"><i class="bi bi-clipboard2-pulse me-1 text-primary"></i>Examenes</h2></div>
            <div class="table-responsive"><table class="table table-hover mb-0"><tbody>
                @foreach($examenes as $item)
                    <tr><td><strong>{{ $item->paciente?->nombre }}</strong><div class="small text-secondary"><i class="bi bi-activity me-1"></i>{{ ucfirst($item->tipo) }} - {{ $item->aprobado ? 'Aprobado' : 'Pendiente' }}</div></td><td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="{{ route('documentos.examenes.show',$item) }}" title="Ver detalles"><i class="bi bi-eye"></i></a> @if(auth()->user()->canModule('documentos','delete'))<form class="d-inline" method="post" action="{{ route('documentos.examenes.destroy',$item) }}" onsubmit="return confirm('Eliminar examen?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button></form>@endif</td></tr>
                @endforeach
            </tbody></table></div><div class="card-footer bg-white">{{ $examenes->links() }}</div>
        </div>
    </div>
</div>
@endsection

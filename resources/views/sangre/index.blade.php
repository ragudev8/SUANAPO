@extends('layouts.app')

@section('title', 'Solicitudes de sangre')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-droplet-half me-2 text-danger"></i>Solicitudes de sangre</h1>
        <p class="text-secondary mb-0">Coordinacion de donantes para hospitales o clinicas externas.</p>
    </div>
</div>

@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<div class="row g-3">
    <div class="col-xl-5">
        @if (auth()->user()->canModule('sangre', 'create'))
            <form class="card border-0 shadow-sm mb-3" method="post" action="{{ route('sangre.solicitudes.store') }}">
                @csrf
                <div class="card-body">
                    <h2 class="h5"><i class="bi bi-plus-circle me-1 text-danger"></i>Nueva solicitud</h2>
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-person-heart me-1"></i>Paciente que necesita sangre</label>
                            <select class="form-select" name="paciente_id">
                                <option value="">No registrado / externo</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id }}">{{ $p->nombre }} - {{ $p->tipo_paciente_label }} - {{ $p->tipo_sangre ?: 'N/D' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Nombre solicitante o paciente externo</label>
                            <input class="form-control" name="solicitante_nombre" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label"><i class="bi bi-hospital me-1"></i>Hospital o clinica</label>
                            <input class="form-control" name="institucion" placeholder="Ej. Hospital Escuela">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-droplet me-1"></i>Tipo sangre</label>
                            <select class="form-select" name="tipo_sangre">@foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $v)<option>{{ $v }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-123 me-1"></i>Pintas</label>
                            <input class="form-control" type="number" min="1" name="cantidad_unidades" value="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input class="form-control" type="date" name="fecha_solicitud" value="{{ now()->toDateString() }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-person-plus me-1"></i>Cadete / policia donante asignado</label>
                            <select class="form-select" name="donante_asignado_id">
                                <option value="">Pendiente de asignar</option>
                                @foreach($donantes as $p)
                                    <option value="{{ $p->id }}">{{ $p->nombre }} - {{ $p->tipo_paciente_label }} - {{ $p->tipo_sangre ?: 'N/D' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Responsable que autoriza</label>
                            <select class="form-select" name="director_id">@foreach($directores as $u)<option value="{{ $u->id }}">{{ $u->nombre }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado"><option>pendiente</option><option>entregada</option><option>rechazada</option></select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Indicaciones</label>
                            <textarea class="form-control" name="indicaciones" rows="2" placeholder="Ej. Presentarse en banco de sangre del hospital con identidad."></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar solicitud</button></div>
            </form>
        @endif

        @if (auth()->user()->canModule('sangre', 'create'))
            <form class="card border-0 shadow-sm" method="post" action="{{ route('sangre.donaciones.store') }}">
                @csrf
                <div class="card-body">
                    <h2 class="h5"><i class="bi bi-droplet-fill me-1 text-danger"></i>Confirmar donacion realizada</h2>
                    <div class="row g-2">
                        <div class="col-12"><label class="form-label">Donante</label><select class="form-select" name="paciente_donante_id" required><option value="">Seleccione...</option>@foreach($donantes as $p)<option value="{{ $p->id }}">{{ $p->nombre }} - {{ $p->tipo_paciente_label }}</option>@endforeach</select></div>
                        <div class="col-4"><label class="form-label">Sangre</label><select class="form-select" name="tipo_sangre">@foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $v)<option>{{ $v }}</option>@endforeach</select></div>
                        <div class="col-4"><label class="form-label">Pintas</label><input class="form-control" type="number" name="cantidad_unidades" min="1" value="1"></div>
                        <div class="col-4"><label class="form-label">Apto</label><select class="form-select" name="estado_salud"><option value="apto">Si</option><option value="no_apto">No</option></select></div>
                        <div class="col-12"><label class="form-label">Fecha</label><input class="form-control" type="date" name="fecha_donacion" value="{{ now()->toDateString() }}"></div>
                        <div class="col-12"><label class="form-label">Hospital / notas</label><textarea class="form-control" name="notas_salud" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Confirmar</button></div>
            </form>
        @endif
    </div>

    <div class="col-xl-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body"><h2 class="h5 mb-0"><i class="bi bi-list-check me-1 text-primary"></i>Solicitudes activas</h2></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th><i class="bi bi-file-medical me-1"></i>Solicitud</th><th><i class="bi bi-person-plus me-1"></i>Donante</th><th><i class="bi bi-activity me-1"></i>Estado</th><th></th></tr></thead>
                    <tbody>
                        @forelse($solicitudes as $s)
                            <tr>
                                <td>
                                    <strong>{{ $s->paciente?->nombre ?? $s->solicitante_nombre }}</strong>
                                    <div class="small text-secondary">{{ $s->institucion ?: 'Hospital pendiente' }} · {{ $s->tipo_sangre }} x {{ $s->cantidad_unidades }}</div>
                                </td>
                                <td>{{ $s->donanteAsignado?->nombre ?? 'Pendiente' }}</td>
                                <td><span class="badge text-bg-light">{{ $s->estado }}</span></td>
                                <td class="text-end">
                                    @if (auth()->user()->canModule('sangre', 'delete'))
                                        <form method="post" action="{{ route('sangre.solicitudes.destroy', $s) }}" onsubmit="return confirm('Eliminar solicitud?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">Sin solicitudes registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">{{ $solicitudes->links() }}</div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body"><h2 class="h5 mb-0"><i class="bi bi-droplet-fill me-1 text-danger"></i>Donaciones confirmadas</h2></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <tbody>
                        @forelse ($donaciones as $d)
                            <tr>
                                <td><strong>{{ $d->pacienteDonante?->nombre }}</strong><div class="small text-secondary">{{ $d->tipo_sangre }} - {{ optional($d->fecha_donacion)->format('d/m/Y') }}</div></td>
                                <td>{{ $d->cantidad_unidades }} pinta(s)</td>
                                <td>{{ str_replace('_', ' ', $d->estado_salud) }}</td>
                                <td class="text-end">
                                    @if (auth()->user()->canModule('sangre', 'delete'))
                                        <form method="post" action="{{ route('sangre.donaciones.destroy', $d) }}" onsubmit="return confirm('Eliminar donacion?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">Sin donaciones confirmadas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">{{ $donaciones->links() }}</div>
        </div>
    </div>
</div>
@endsection

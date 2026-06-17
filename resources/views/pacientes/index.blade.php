@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="bi bi-people me-2 text-primary"></i>Pacientes</h1>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="{{ route('pacientes.export') }}"><i class="bi bi-download me-1"></i>Exportar</a>
        @if (auth()->user()->canModule('pacientes', 'create'))
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#importPacientes">
                <i class="bi bi-upload me-1"></i>Importar
            </button>
            <a class="btn btn-success" href="{{ route('pacientes.create') }}"><i class="bi bi-person-plus me-1"></i>Nuevo</a>
        @endif
    </div>
</div>

@if (auth()->user()->canModule('pacientes', 'create'))
    <div class="collapse mb-3" id="importPacientes">
        <form class="card border-0 shadow-sm" method="post" action="{{ route('imports.pacientes') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body row g-2 align-items-end">
                <div class="col-md">
                    <label class="form-label"><i class="bi bi-file-earmark-spreadsheet me-1"></i>Archivo Excel/CSV de pacientes</label>
                    <input class="form-control" type="file" name="archivo" accept=".xlsx,.xls,.csv" required>
                    <div class="form-text">Columnas: nombre, dni, fecha_nacimiento, sexo, estado_civil, grado_militar, ocupacion, unidad_dependencia, numero_placa, tipo_sangre, alergias, observaciones, telefono, celular, correo, direccion, contacto_emergencia_nombre, contacto_emergencia_telefono, responsable_nombre, responsable_parentesco. En grado_militar puede usar: Cadete, Oficial, Escala_Basica, Personal_Administrativo, Civil, Beneficiario, Instructor o Aspirante.</div>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-success"><i class="bi bi-upload me-1"></i>Cargar datos</button>
                </div>
            </div>
        </form>
    </div>
@endif

<form class="mb-3" method="get">
    <div class="input-group">
        <input class="form-control" name="q" value="{{ $q }}" placeholder="Buscar por nombre o DNI">
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th><i class="bi bi-person me-1"></i>Paciente</th><th><i class="bi bi-info-circle me-1"></i>Datos</th><th><i class="bi bi-telephone me-1"></i>Contacto</th><th></th></tr></thead>
            <tbody>
                @forelse ($pacientes as $paciente)
                    <tr>
                        <td>
                            <strong>{{ $paciente->nombre }}</strong>
                            <div class="small text-secondary"><i class="bi bi-person-vcard me-1"></i>DNI {{ $paciente->dni }}</div>
                        </td>
                        <td>
                            <i class="bi {{ $paciente->vinculo_institucional_icon }} me-1"></i>{{ $paciente->vinculo_institucional_label }}
                            <div class="small text-secondary">{{ $paciente->tipo_paciente_label }}</div>
                            <div class="small text-secondary">{{ $paciente->sexo ?: 'N/D' }} - {{ $paciente->edad ? $paciente->edad.' anios' : 'Edad N/D' }} - {{ $paciente->tipo_sangre ?? 'Sangre N/D' }}</div>
                        </td>
                        <td>
                            {{ $paciente->celular ?: ($paciente->telefono ?: 'N/D') }}
                            <div class="small text-secondary text-break">{{ $paciente->correo ?: ($paciente->unidad_dependencia ?: 'Sin correo') }}</div>
                        </td>
                        <td class="text-end text-nowrap">
                            @if (auth()->user()->canModule('pacientes', 'view'))
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('pacientes.show', $paciente) }}" title="Ver"><i class="bi bi-eye"></i></a>
                            @endif
                            @if (auth()->user()->canModule('pacientes', 'edit'))
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('pacientes.edit', $paciente) }}" title="Editar"><i class="bi bi-pencil"></i></a>
                            @endif
                            @if (auth()->user()->canModule('pacientes', 'delete'))
                                <form class="d-inline" method="post" action="{{ route('pacientes.destroy', $paciente) }}" onsubmit="return confirm('Eliminar este paciente?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">Sin pacientes registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
    <div class="small text-secondary">
        @if ($pacientes->total() > 0)
            Mostrando {{ $pacientes->firstItem() }} a {{ $pacientes->lastItem() }} de {{ $pacientes->total() }} pacientes
        @else
            No hay pacientes para mostrar
        @endif
    </div>
    <div>{{ $pacientes->links() }}</div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Medicamentos')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="bi bi-capsule me-2 text-success"></i>Medicamentos</h1>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="{{ route('medicamentos.export') }}"><i class="bi bi-download me-1"></i>Exportar</a>
        @if (auth()->user()->canModule('medicamentos', 'create'))
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#importMedicamentos">
                <i class="bi bi-upload me-1"></i>Importar
            </button>
        @endif
    </div>
</div>

@if (auth()->user()->canModule('medicamentos', 'create'))
    <div class="collapse mb-3" id="importMedicamentos">
        <form class="card border-0 shadow-sm" method="post" action="{{ route('imports.medicamentos') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body row g-2 align-items-end">
                <div class="col-md">
                    <label class="form-label"><i class="bi bi-file-earmark-spreadsheet me-1"></i>Archivo Excel/CSV de medicamentos</label>
                    <input class="form-control" type="file" name="archivo" accept=".xlsx,.xls,.csv" required>
                    <div class="form-text">Columnas: nombre, presentacion, dosis, cantidad_stock, cantidad_minima, fecha_vencimiento, lote, precio_costo.</div>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-success"><i class="bi bi-upload me-1"></i>Cargar datos</button>
                </div>
            </div>
        </form>
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-4">
        @if (auth()->user()->canModule('medicamentos', 'create'))
        <form class="card border-0 shadow-sm" method="post" action="{{ route('medicamentos.store') }}">
            @csrf
            <div class="card-body">
                <h2 class="h5"><i class="bi bi-plus-circle me-1 text-success"></i>Nuevo medicamento</h2>
                <div class="mb-2"><label class="form-label"><i class="bi bi-capsule me-1"></i>Nombre</label><input class="form-control" name="nombre" required></div>
                <div class="mb-2"><label class="form-label"><i class="bi bi-box-seam me-1"></i>Presentacion</label><input class="form-control" name="presentacion"></div>
                <div class="mb-2"><label class="form-label"><i class="bi bi-prescription2 me-1"></i>Dosis</label><input class="form-control" name="dosis"></div>
                <div class="row g-2">
                    <div class="col"><label class="form-label"><i class="bi bi-boxes me-1"></i>Stock</label><input class="form-control" type="number" name="cantidad_stock" min="0" value="0"></div>
                    <div class="col"><label class="form-label"><i class="bi bi-exclamation-triangle me-1"></i>Minimo</label><input class="form-control" type="number" name="cantidad_minima" min="0" value="10"></div>
                </div>
                <div class="mt-2"><label class="form-label"><i class="bi bi-calendar2-x me-1"></i>Vencimiento</label><input class="form-control" type="date" name="fecha_vencimiento"></div>
                <div class="mt-2"><label class="form-label"><i class="bi bi-upc-scan me-1"></i>Lote</label><input class="form-control" name="lote"></div>
            </div>
            <div class="card-footer bg-white text-end"><button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Guardar</button></div>
        </form>
        @else
            <div class="alert alert-light border">Tu rol puede consultar medicamentos, pero no crear nuevos.</div>
        @endif
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th><i class="bi bi-capsule me-1"></i>Medicamento</th><th><i class="bi bi-boxes me-1"></i>Stock</th><th><i class="bi bi-calendar2-x me-1"></i>Vence</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($medicamentos as $medicamento)
                            <tr>
                                <td><strong>{{ $medicamento->nombre }}</strong><div class="small text-secondary">{{ $medicamento->presentacion }} {{ $medicamento->dosis }}</div></td>
                                <td>{{ $medicamento->cantidad_stock }}</td>
                                <td>{{ optional($medicamento->fecha_vencimiento)->format('d/m/Y') ?? 'N/D' }}</td>
                                <td class="text-end text-nowrap">
                                    @if (auth()->user()->canModule('medicamentos', 'view'))
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('medicamentos.show', $medicamento) }}"><i class="bi bi-eye"></i></a>
                                    @endif
                                    @if (auth()->user()->canModule('medicamentos', 'edit'))
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('medicamentos.edit', $medicamento) }}"><i class="bi bi-pencil"></i></a>
                                    @endif
                                    @if (auth()->user()->canModule('medicamentos', 'delete'))
                                        <form class="d-inline" method="post" action="{{ route('medicamentos.destroy', $medicamento) }}" onsubmit="return confirm('Eliminar este medicamento?')">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $medicamentos->links() }}</div>
    </div>
</div>
@endsection

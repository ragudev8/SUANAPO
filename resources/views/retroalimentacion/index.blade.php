@extends('layouts.app')

@section('title', 'Retroalimentacion')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-0"><i class="bi bi-chat-square-heart me-2 text-primary"></i>Retroalimentacion</h1>
        <p class="text-secondary mb-0">Ideas, errores y mejoras reportadas por los usuarios.</p>
    </div>
    @if (auth()->user()->canModule('retroalimentacion', 'create'))
        <a class="btn btn-success" href="{{ route('retroalimentacion.create') }}">
            <i class="bi bi-send-plus me-1"></i>Enviar idea
        </a>
    @endif
</div>

<form class="row g-2 mb-3" method="get">
    <div class="col-md-7">
        <input class="form-control" name="q" value="{{ $q }}" placeholder="Buscar por asunto, mensaje o modulo">
    </div>
    <div class="col-md-3">
        <select class="form-select" name="estado">
            <option value="">Todos los estados</option>
            @foreach ($estados as $item)
                <option value="{{ $item }}" @selected($estado === $item)>{{ ucfirst($item) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search me-1"></i>Buscar</button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th><i class="bi bi-lightbulb me-1"></i>Solicitud</th>
                    <th><i class="bi bi-flag me-1"></i>Estado</th>
                    <th><i class="bi bi-person me-1"></i>Usuario</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($retroalimentaciones as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->asunto }}</strong>
                            <div class="small text-secondary">
                                <i class="bi bi-grid-3x3-gap me-1"></i>{{ $modulos[$item->modulo] ?? ($item->modulo ?: 'Sistema general') }}
                                <span class="mx-1">-</span>
                                <i class="bi {{ match($item->tipo) {
                                    'error' => 'bi-bug',
                                    'nuevo_modulo' => 'bi-plus-square',
                                    'diseno' => 'bi-palette',
                                    'otro' => 'bi-three-dots',
                                    default => 'bi-stars',
                                } }} me-1"></i>{{ str_replace('_', ' ', $item->tipo) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ match($item->estado) {
                                'pendiente' => 'text-bg-warning',
                                'revisando' => 'text-bg-primary',
                                'aceptada' => 'text-bg-success',
                                default => 'text-bg-secondary',
                            } }}">
                                <i class="bi {{ match($item->estado) {
                                    'pendiente' => 'bi-hourglass-split',
                                    'revisando' => 'bi-search',
                                    'aceptada' => 'bi-check-circle',
                                    default => 'bi-archive',
                                } }} me-1"></i>{{ ucfirst($item->estado) }}
                            </span>
                            <div class="small text-secondary">
                                <i class="bi {{ $item->prioridad === 'alta' ? 'bi-exclamation-triangle' : ($item->prioridad === 'media' ? 'bi-exclamation-circle' : 'bi-arrow-down-circle') }} me-1"></i>Prioridad {{ $item->prioridad }}
                            </div>
                        </td>
                        <td>
                            {{ $item->usuario?->nombre ?? 'N/A' }}
                            <div class="small text-secondary"><i class="bi bi-clock me-1"></i>{{ $item->created_at?->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('retroalimentacion.show', $item) }}" title="Ver"><i class="bi bi-eye"></i></a>
                            @if (auth()->user()->esAdmin())
                                <form class="d-inline" method="post" action="{{ route('retroalimentacion.destroy', $item) }}" onsubmit="return confirm('Eliminar esta retroalimentacion?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">No hay retroalimentacion registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
    <div class="small text-secondary">
        @if ($retroalimentaciones->total() > 0)
            Mostrando {{ $retroalimentaciones->firstItem() }} a {{ $retroalimentaciones->lastItem() }} de {{ $retroalimentaciones->total() }} registros
        @else
            No hay registros para mostrar
        @endif
    </div>
    <div>{{ $retroalimentaciones->links() }}</div>
</div>
@endsection

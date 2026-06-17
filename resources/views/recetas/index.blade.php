@extends('layouts.app')

@section('title', 'Recetas')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="bi bi-prescription2 me-2 text-primary"></i>Recetas</h1>
    @if (auth()->user()->canModule('recetas', 'create'))
        <a class="btn btn-success" href="{{ route('recetas.create') }}"><i class="bi bi-plus-lg me-1"></i>Nueva receta</a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th><i class="bi bi-qr-code me-1"></i>Receta</th><th><i class="bi bi-person me-1"></i>Paciente</th><th><i class="bi bi-activity me-1"></i>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse ($recetas as $receta)
                    @php($locked = $receta->detalles->contains(fn ($detalle) => $detalle->dispensado))
                    <tr>
                        <td><strong>{{ $receta->folio_unico }}</strong><div class="small text-secondary"><i class="bi bi-calendar-event me-1"></i>{{ optional($receta->fecha_emision)->format('d/m/Y') }} - <i class="bi bi-capsule me-1"></i>{{ $receta->detalles->first()?->medicamento?->nombre ?? 'Sin detalle' }}</div></td>
                        <td>{{ $receta->paciente?->nombre ?? 'N/D' }}</td>
                        <td><span class="badge text-bg-light">{{ ucfirst($receta->estado) }}</span><div class="small text-secondary">{{ $receta->medico?->nombre ?? 'N/D' }}</div></td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('recetas.show', $receta) }}"><i class="bi bi-eye"></i></a>
                            @if (auth()->user()->canModule('recetas', 'edit') && (! $locked || auth()->user()->esAdmin()))
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('recetas.edit', $receta) }}"><i class="bi bi-pencil"></i></a>
                            @endif
                            @if (auth()->user()->canModule('recetas', 'delete') && (! $locked || auth()->user()->esAdmin()))
                                <form class="d-inline" method="post" action="{{ route('recetas.destroy', $receta) }}" onsubmit="return confirm('Eliminar esta receta?')">
                                    @csrf @method('delete')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">Sin recetas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $recetas->links() }}</div>
@endsection

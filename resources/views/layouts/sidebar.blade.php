<div class="list-group">
    <div class="sidebar-section-title"><i class="bi bi-hospital me-1"></i>Clinica</div>
    <a class="list-group-item list-group-item-action" href="{{ route('dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    @if (auth()->user()->canModule('atenciones'))
        <a class="list-group-item list-group-item-action" href="{{ route('atenciones.board') }}">
            <i class="bi bi-kanban me-2"></i>Board de atenciones
        </a>
    @endif
    @if (auth()->user()->canModule('pacientes'))
        <a class="list-group-item list-group-item-action" href="{{ route('pacientes.index') }}">
            <i class="bi bi-people me-2"></i>Pacientes
        </a>
    @endif
    @if (auth()->user()->canModule('medicamentos'))
        <a class="list-group-item list-group-item-action" href="{{ route('medicamentos.index') }}">
            <i class="bi bi-capsule me-2"></i>Medicamentos
        </a>
    @endif
    @foreach ([
        'recetas' => ['icon' => 'bi-qr-code', 'label' => 'Recetas'],
        'documentos' => ['icon' => 'bi-file-earmark-medical', 'label' => 'Documentos'],
        'sangre' => ['icon' => 'bi-droplet-half', 'label' => 'Sangre'],
        'reportes' => ['icon' => 'bi-graph-up-arrow', 'label' => 'Reportes'],
        'auditoria' => ['icon' => 'bi-clipboard-data', 'label' => 'Auditoria'],
        'retroalimentacion' => ['icon' => 'bi-chat-square-text', 'label' => 'Retroalimentacion'],
    ] as $module => $item)
        @if (auth()->user()->canModule($module))
            <a class="list-group-item list-group-item-action" href="{{ match($module) {
                'reportes' => route('reportes.index'),
                'recetas' => route('recetas.index'),
                'documentos' => route('documentos.index'),
                'sangre' => route('sangre.index'),
                'auditoria' => route('auditoria.index'),
                'retroalimentacion' => route('retroalimentacion.index'),
                default => route('modulos.show', $module),
            } }}">
                <i class="bi {{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
            </a>
        @endif
    @endforeach
    @if (auth()->user()->canModule('soporte_dashboard'))
        <div class="sidebar-section-title mt-3"><i class="bi bi-pc-display me-1"></i>Soporte TI</div>
        <a class="list-group-item list-group-item-action" href="{{ route('soporte.dashboard') }}">
            <i class="bi bi-speedometer me-2"></i>Panel Soporte
        </a>
    @endif
    @if (auth()->user()->canModule('usuarios'))
        <div class="sidebar-section-title mt-3"><i class="bi bi-gear me-1"></i>Administracion</div>
        <a class="list-group-item list-group-item-action" href="{{ route('usuarios.index') }}">
            <i class="bi bi-person-gear me-2"></i>Usuarios y roles
        </a>
        <a class="list-group-item list-group-item-action" href="{{ route('permisos.index') }}">
            <i class="bi bi-shield-lock me-2"></i>Matriz de permisos
        </a>
    @endif
</div>

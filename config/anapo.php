<?php

return array (
  'systems' => 
  array (
    'clinica' => 
    array (
      'label' => 'Clinica ANAPO',
      'icon' => 'bi-hospital',
      'modules' => 
      array (
        0 => 'dashboard',
        1 => 'atenciones',
        2 => 'pacientes',
        3 => 'medicamentos',
        4 => 'recetas',
        5 => 'documentos',
        6 => 'sangre',
        7 => 'reportes',
        8 => 'retroalimentacion',
      ),
    ),
    'soporte' => 
    array (
      'label' => 'Soporte TI',
      'icon' => 'bi-pc-display',
      'modules' => 
      array (
        0 => 'soporte_dashboard',
        1 => 'soporte_tickets',
        2 => 'soporte_equipos',
        3 => 'soporte_reportes',
      ),
    ),
    'administracion' => 
    array (
      'label' => 'Administracion',
      'icon' => 'bi-gear',
      'modules' => 
      array (
        0 => 'usuarios',
        1 => 'auditoria',
      ),
    ),
  ),
  'modules' => 
  array (
    'dashboard' => 'Dashboard',
    'usuarios' => 'Usuarios y roles',
    'pacientes' => 'Pacientes',
    'atenciones' => 'Atenciones y visitas',
    'medicamentos' => 'Medicamentos',
    'recetas' => 'Recetas',
    'documentos' => 'Incapacidades y constancias',
    'sangre' => 'Donacion de sangre',
    'reportes' => 'Reportes',
    'auditoria' => 'Auditoria',
    'retroalimentacion' => 'Retroalimentacion',
    'soporte_dashboard' => 'Soporte TI - Dashboard',
    'soporte_tickets' => 'Soporte TI - Tickets',
    'soporte_equipos' => 'Soporte TI - Equipos',
    'soporte_reportes' => 'Soporte TI - Reportes',
  ),
  'actions' => 
  array (
    0 => 'view',
    1 => 'create',
    2 => 'edit',
    3 => 'delete',
  ),
  'patient_types' => 
  array (
    'Cadete' => 'Cadete',
    'Oficial' => 'Oficial',
    'Escala_Basica' => 'Escala basica',
    'Personal_Administrativo' => 'Personal administrativo',
    'Civil' => 'Civil',
    'Beneficiario' => 'Beneficiario',
    'Instructor' => 'Instructor',
    'Aspirante' => 'Aspirante',
    'Personal' => 'Personal',
  ),
  'permissions' => 
  array (
    'super_admin' => 
    array (
      '*' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
    ),
    'admin' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'usuarios' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'pacientes' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
      'atenciones' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
      'medicamentos' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
      'recetas' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'documentos' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'sangre' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
      'reportes' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
      'auditoria' => 
      array (
        0 => 'view',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
    ),
    'medico' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'pacientes' => 
      array (
        0 => 'view',
      ),
      'atenciones' => 
      array (
        0 => 'view',
        1 => 'edit',
      ),
      'medicamentos' => 
      array (
        0 => 'view',
      ),
      'recetas' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'documentos' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
    ),
    'enfermero_media' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'pacientes' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'atenciones' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'medicamentos' => 
      array (
        0 => 'view',
        1 => 'edit',
      ),
      'recetas' => 
      array (
        0 => 'view',
      ),
      'sangre' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
    ),
    'licenciado_enfermeria' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'pacientes' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'atenciones' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'medicamentos' => 
      array (
        0 => 'view',
        1 => 'edit',
      ),
      'recetas' => 
      array (
        0 => 'view',
      ),
      'sangre' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
      'documentos' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
    ),
    'soporte_ti' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'soporte_dashboard' => 
      array (
        0 => 'view',
      ),
      'soporte_tickets' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
      'soporte_equipos' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
        3 => 'delete',
      ),
      'soporte_reportes' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
        2 => 'edit',
      ),
    ),
    'docente' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
    ),
    'administrativo_academia' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'soporte_dashboard' => 
      array (
        0 => 'view',
      ),
      'soporte_tickets' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
    ),
    'paciente' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'pacientes' => 
      array (
        0 => 'view',
      ),
      'atenciones' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
      'recetas' => 
      array (
        0 => 'view',
      ),
      'documentos' => 
      array (
        0 => 'view',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
    ),
    'auditor' => 
    array (
      'dashboard' => 
      array (
        0 => 'view',
      ),
      'reportes' => 
      array (
        0 => 'view',
      ),
      'auditoria' => 
      array (
        0 => 'view',
      ),
      'retroalimentacion' => 
      array (
        0 => 'view',
        1 => 'create',
      ),
    ),
  ),
);

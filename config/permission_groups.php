<?php

/**
 * Permission groups for custom roles.
 * Each group maps to a set of route names allowed for that permission.
 * System roles (admin, recepcion, taller) use config/permissions.php instead.
 */

return [

    // ── Órdenes de Trabajo ──────────────────────────────────────
    'ot_read' => [
        'label'  => 'Ver órdenes de trabajo',
        'icon'   => 'bi-eye',
        'routes' => ['work-orders.index', 'work-orders.show', 'work-orders.pdf', 'work-orders.intake-pdf'],
    ],

    'ot_write' => [
        'label'  => 'Crear y editar OTs',
        'icon'   => 'bi-wrench',
        'routes' => [
            'work-orders.create', 'work-orders.store', 'work-orders.edit', 'work-orders.update',
            'work-orders.status', 'work-orders.followup', 'work-orders.toggle-approval',
            'work-orders.invoice-pdf',
            'clients.search', 'clients.quickStore',
            'vehicles.search', 'vehicles.quickStore',
            'service-items.search', 'parts.search', 'parts.quickStore', 'un-types.json',
            'insurance-companies.quickStore', 'liquidators.quickStore',
            'vehicle-brands.json', 'vehicle-brands.models.json',
        ],
    ],

    'ot_delete' => [
        'label'  => 'Eliminar OTs',
        'icon'   => 'bi-trash',
        'routes' => ['work-orders.destroy'],
    ],

    // ── Repuestos y Pedidos ─────────────────────────────────────
    'part_orders' => [
        'label'  => 'Gestionar pedidos de repuestos',
        'icon'   => 'bi-box-seam',
        'routes' => ['part-orders.store', 'part-orders.update', 'part-orders.destroy', 'part-orders.receive'],
    ],

    // ── Clientes y Vehículos ────────────────────────────────────
    'clients_read' => [
        'label'  => 'Ver clientes y vehículos',
        'icon'   => 'bi-people',
        'routes' => [
            'clients.index', 'clients.show', 'clients.search',
            'vehicles.index', 'vehicles.show', 'vehicles.search',
        ],
    ],

    'clients_write' => [
        'label'  => 'Crear / Editar clientes y vehículos',
        'icon'   => 'bi-person-plus',
        'routes' => [
            'clients.create', 'clients.store', 'clients.edit', 'clients.update', 'clients.quickStore',
            'vehicles.create', 'vehicles.store', 'vehicles.edit', 'vehicles.update', 'vehicles.quickStore',
            'vehicle-brands.json', 'vehicle-brands.models.json',
        ],
    ],

    'clients_delete' => [
        'label'  => 'Eliminar clientes y vehículos',
        'icon'   => 'bi-person-x',
        'routes' => ['clients.destroy', 'vehicles.destroy'],
    ],

    // ── Seguros y Liquidadores ──────────────────────────────────
    'insurance_liquidators' => [
        'label'  => 'Aseguradoras y liquidadores',
        'icon'   => 'bi-shield-check',
        'routes' => [
            'insurance-companies.index', 'insurance-companies.store',
            'insurance-companies.update', 'insurance-companies.destroy', 'insurance-companies.quickStore',
            'liquidators.index', 'liquidators.store',
            'liquidators.update', 'liquidators.destroy', 'liquidators.quickStore',
        ],
    ],

    // ── Reportes ────────────────────────────────────────────────
    'reports' => [
        'label'  => 'Reportes y estadísticas',
        'icon'   => 'bi-graph-up-arrow',
        'routes' => [
            'reports.index', 'reports.pdf', 'reports.insurance',
            'reports.profitability', 'reports.parts', 'reports.billing',
        ],
    ],

    // ── Seguimiento y SLA ───────────────────────────────────────
    'followup_sla' => [
        'label'  => 'Seguimiento y control de tiempos',
        'icon'   => 'bi-clock-history',
        'routes' => ['work-orders.followup', 'sla.index', 'sla.update'],
    ],

    // ── Catálogos ───────────────────────────────────────────────
    'catalogs' => [
        'label'  => 'Administrar catálogos',
        'icon'   => 'bi-gear',
        'routes' => [
            'parts.index', 'parts.store', 'parts.update', 'parts.destroy',
            'service-items.index', 'service-items.create', 'service-items.store',
            'service-items.edit', 'service-items.update', 'service-items.destroy', 'service-items.store-type',
            'un-types.index', 'un-types.store', 'un-types.update', 'un-types.destroy',
            'vehicle-brands.index', 'vehicle-brands.store', 'vehicle-brands.update', 'vehicle-brands.destroy',
            'vehicle-brands.models.store',
        ],
    ],

    // ── Etiquetas ───────────────────────────────────────────────
    'tags' => [
        'label'  => 'Gestionar etiquetas',
        'icon'   => 'bi-bookmark',
        'routes' => ['tags.index', 'tags.store', 'tags.update', 'tags.destroy'],
    ],

    // ── Feriados ────────────────────────────────────────────────
    'holidays' => [
        'label'  => 'Gestionar feriados',
        'icon'   => 'bi-calendar-event',
        'routes' => ['holidays.index', 'holidays.store', 'holidays.destroy', 'holidays.seed'],
    ],

    // ── Usuarios y Roles ────────────────────────────────────────
    'users_management' => [
        'label'  => 'Administrar usuarios y roles',
        'icon'   => 'bi-people-fill',
        'routes' => [
            'users.index', 'users.store', 'users.update', 'users.destroy',
            'roles.index', 'roles.store', 'roles.update', 'roles.destroy',
        ],
    ],

    // ── Sucursales ──────────────────────────────────────────────
    'branches' => [
        'label'  => 'Administrar sucursales',
        'icon'   => 'bi-shop',
        'routes' => [
            'branches.index', 'branches.store', 'branches.update', 'branches.destroy',
            'branch.switch',
        ],
    ],

];

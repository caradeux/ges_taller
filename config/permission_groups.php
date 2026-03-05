<?php

/**
 * Permission groups for custom roles.
 * Each group maps to a set of route names allowed for that permission.
 * System roles (admin, recepcion, taller) use config/permissions.php instead.
 */

return [

    'quotations_read' => [
        'label' => 'Ver cotizaciones',
        'icon'  => 'bi-eye',
        'routes' => ['quotations.index', 'quotations.show', 'quotations.pdf'],
    ],

    'quotations_write' => [
        'label' => 'Crear y editar cotizaciones',
        'icon'  => 'bi-file-earmark-plus',
        'routes' => [
            'quotations.create', 'quotations.store', 'quotations.edit', 'quotations.update',
            'quotations.status', 'quotations.followup',
            // APIs needed by the quotation form
            'clients.search', 'clients.quickStore',
            'vehicles.search', 'vehicles.quickStore',
            'service-items.search', 'un-types.json',
            'insurance-companies.quickStore', 'liquidators.quickStore',
            'vehicle-brands.json', 'vehicle-brands.models.json',
        ],
    ],

    'quotations_delete' => [
        'label' => 'Eliminar cotizaciones',
        'icon'  => 'bi-trash',
        'routes' => ['quotations.destroy'],
    ],

    'clients_read' => [
        'label' => 'Ver clientes y vehículos',
        'icon'  => 'bi-people',
        'routes' => [
            'clients.index', 'clients.show', 'clients.search',
            'vehicles.index', 'vehicles.show', 'vehicles.search',
        ],
    ],

    'clients_write' => [
        'label' => 'Crear / Editar clientes y vehículos',
        'icon'  => 'bi-person-plus',
        'routes' => [
            'clients.create', 'clients.store', 'clients.edit', 'clients.update', 'clients.quickStore',
            'vehicles.create', 'vehicles.store', 'vehicles.edit', 'vehicles.update', 'vehicles.quickStore',
            'vehicle-brands.json', 'vehicle-brands.models.json',
        ],
    ],

    'clients_delete' => [
        'label' => 'Eliminar clientes y vehículos',
        'icon'  => 'bi-person-x',
        'routes' => ['clients.destroy', 'vehicles.destroy'],
    ],

    'reports' => [
        'label' => 'Reportes',
        'icon'  => 'bi-bar-chart-line',
        'routes' => ['reports.index', 'reports.pdf'],
    ],

    'insurance_liquidators' => [
        'label' => 'Liquidadores y compañías de seguros',
        'icon'  => 'bi-building',
        'routes' => [
            'insurance-companies.index', 'insurance-companies.store',
            'insurance-companies.update', 'insurance-companies.destroy', 'insurance-companies.quickStore',
            'liquidators.index', 'liquidators.store',
            'liquidators.update', 'liquidators.destroy', 'liquidators.quickStore',
        ],
    ],

];

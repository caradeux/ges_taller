<?php

/**
 * Role-based route permissions.
 *
 * Keys are route name patterns (supports wildcard *).
 * Values are arrays of roles allowed to access that route.
 * 'admin' always has full access regardless of this config.
 */

return [

    // ── Route patterns => allowed roles ───────────────────────────────────

    'dashboard'                  => ['admin', 'recepcion', 'taller'],

    // Work Orders (OT)
    'work-orders.index'          => ['admin', 'recepcion', 'taller'],
    'work-orders.show'           => ['admin', 'recepcion', 'taller'],
    'work-orders.create'         => ['admin', 'recepcion'],
    'work-orders.store'          => ['admin', 'recepcion'],
    'work-orders.edit'           => ['admin', 'recepcion'],
    'work-orders.update'         => ['admin', 'recepcion'],
    'work-orders.destroy'        => ['admin'],
    'work-orders.followup'       => ['admin', 'recepcion'],
    'work-orders.pdf'            => ['admin', 'recepcion', 'taller'],
    'work-orders.invoice-pdf'    => ['admin', 'recepcion'],
    'work-orders.status'         => ['admin', 'recepcion'],
    'work-orders.toggle-approval' => ['admin', 'recepcion'],

    // Part Orders
    'part-orders.*'              => ['admin', 'recepcion'],

    // Tags
    'tags.*'                     => ['admin'],
    'tags.search'                => ['admin', 'recepcion'],

    // Clients
    'clients.search'             => ['admin', 'recepcion', 'taller'],
    'clients.quickStore'         => ['admin', 'recepcion'],
    'clients.index'              => ['admin', 'recepcion', 'taller'],
    'clients.show'               => ['admin', 'recepcion', 'taller'],
    'clients.create'             => ['admin', 'recepcion'],
    'clients.store'              => ['admin', 'recepcion'],
    'clients.edit'               => ['admin', 'recepcion'],
    'clients.update'             => ['admin', 'recepcion'],
    'clients.destroy'            => ['admin'],

    // Vehicles
    'vehicles.search'            => ['admin', 'recepcion', 'taller'],
    'vehicles.quickStore'        => ['admin', 'recepcion'],
    'vehicles.index'             => ['admin', 'recepcion', 'taller'],
    'vehicles.show'              => ['admin', 'recepcion', 'taller'],
    'vehicles.create'            => ['admin', 'recepcion'],
    'vehicles.store'             => ['admin', 'recepcion'],
    'vehicles.edit'              => ['admin', 'recepcion'],
    'vehicles.update'            => ['admin', 'recepcion'],
    'vehicles.destroy'           => ['admin'],

    // Insurance companies
    'insurance-companies.quickStore' => ['admin', 'recepcion'],
    'insurance-companies.*'      => ['admin', 'recepcion'],

    // Liquidators
    'liquidators.quickStore'     => ['admin', 'recepcion'],
    'liquidators.*'              => ['admin', 'recepcion'],

    // Reports
    'reports.*'                  => ['admin', 'recepcion'],

    // Profile (always own)
    'profile.*'                  => ['admin', 'recepcion', 'taller'],

    // Service items catalog (read for autocomplete)
    'service-items.index'        => ['admin'],
    'service-items.create'       => ['admin'],
    'service-items.store'        => ['admin'],
    'service-items.edit'         => ['admin'],
    'service-items.update'       => ['admin'],
    'service-items.destroy'      => ['admin'],
    'service-items.search'       => ['admin', 'recepcion', 'taller'],

    // UN Types catalog (admin only)
    'un-types.*'                 => ['admin'],
    'un-types.json'              => ['admin', 'recepcion'],

    // Vehicle brands catalog
    'vehicle-brands.*'           => ['admin'],
    'vehicle-brands.json'        => ['admin', 'recepcion'],
    'vehicle-brands.models.json' => ['admin', 'recepcion'],

    // Roles management (admin only)
    'roles.index'                => ['admin'],
    'roles.store'                => ['admin'],
    'roles.update'               => ['admin'],
    'roles.destroy'              => ['admin'],

    // Users management (admin only)
    'users.*'                    => ['admin'],

    // Branches management (admin only)
    'branches.*'                 => ['admin'],
    'branch.switch'              => ['admin'],
];

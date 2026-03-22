@extends('layouts.app')

@section('title', 'Nueva Orden de Trabajo')

@section('styles')
<style>
    /* ─── Items table ─────────────────────────────────── */
    .items-table th {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--text-muted);
        background: #f8f9fb;
        padding: 0.75rem 0.875rem;
        border-bottom: 1px solid var(--border);
        border-top: none;
        white-space: nowrap;
    }

    .items-table td {
        font-size: 0.845rem;
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-light);
    }

    .items-table tbody tr:last-child td { border-bottom: none; }
    .items-table tbody tr:hover td { background: #f7f8ff; }

    .items-table .un-sel {
        min-width: 145px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .items-table .price-inp {
        width: 100px;
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .items-table .rm-row {
        opacity: 0;
        color: var(--danger);
        border: none;
        background: transparent;
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        transition: opacity 0.15s ease, background 0.15s ease;
        cursor: pointer;
    }

    .items-table tbody tr:hover .rm-row { opacity: 1; }
    .items-table .rm-row:hover { background: var(--danger-light); }

    /* ─── Inline add button (input-group) ─────────────── */
    .btn-add-inline {
        border: 1px solid var(--border);
        border-left: none;
        background: white;
        color: var(--primary);
        padding: 0 0.75rem;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0 !important;
        transition: var(--transition);
        font-size: 0.875rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .btn-add-inline:hover {
        background: var(--primary-light);
        border-color: var(--primary-border);
        color: var(--primary-dark);
    }

    /* ─── Autocomplete ────────────────────────────────── */
    .ac-wrap { position: relative; flex: 1 1 auto; min-width: 0; }
    .ac-dropdown {
        display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 9999;
        background: #fff; border: 1px solid var(--border);
        border-radius: 0 0 var(--radius-sm) var(--radius-sm);
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
        max-height: 210px; overflow-y: auto; list-style: none; padding: 0; margin: 0;
    }
    /* Allow autocomplete dropdown to overflow table-responsive */
    .table-responsive { overflow: visible !important; }
    .items-table td { overflow: visible; }
    .ac-dropdown li {
        padding: 0.5rem 0.875rem; cursor: pointer; font-size: 0.84rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .ac-dropdown li:last-child { border-bottom: none; }
    .ac-dropdown li:hover, .ac-dropdown li.ac-active { background: #f0f4ff; }

    /* ─── Section title inside card ────────────────────── */
    .card-section-label {
        font-size: 0.67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 1.125rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-light);
    }

    /* ─── Add-row link area ────────────────────────────── */
    .add-row-strip {
        padding: 0.6rem 0.875rem;
        border-top: 1px dashed var(--border);
        background: transparent;
    }

    .btn-add-row-link {
        background: transparent;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0;
        transition: var(--transition);
    }

    .btn-add-row-link:hover { color: var(--primary); }
</style>
@endsection

@section('content')
<div class="animate-in">

    {{-- ─── Page Header ────────────────────────────────── --}}
    <div class="d-flex align-items-start justify-content-between mb-4">
        <div>
            <a href="{{ route('work-orders.index') }}"
                class="d-inline-flex align-items-center gap-1 text-decoration-none mb-2"
                style="font-size:0.78rem;font-weight:600;color:var(--text-muted);">
                <i class="bi bi-arrow-left"></i> Ordenes de Trabajo
            </a>
            <h2 class="page-title mb-0">Nueva Orden de Trabajo</h2>
        </div>
        <div class="d-flex gap-2 align-items-center pt-1">
            <a href="{{ route('work-orders.index') }}" class="btn-app-secondary">
                Cancelar
            </a>
            <button type="submit" form="workOrderForm" class="btn-primary-premium">
                <i class="bi bi-check-circle-fill"></i> Guardar OT
            </button>
        </div>
    </div>

    <form action="{{ route('work-orders.store') }}" method="POST" id="workOrderForm">
        @csrf
        <div class="row g-4 align-items-start">

            {{-- ═══════════════════════════════════════════ --}}
            {{-- Columna Izquierda                          --}}
            {{-- ═══════════════════════════════════════════ --}}
            <div class="col-lg-4 d-flex flex-column gap-3">

                {{-- Card 1: Expediente ── --}}
                <div class="card p-4">
                    <div class="card-section-label">
                        <i class="bi bi-file-earmark-text-fill" style="color:var(--primary);font-size:0.85rem;"></i>
                        Datos del Expediente
                    </div>

                    {{-- Cliente --}}
                    <div class="mb-3">
                        <label class="form-label">Cliente <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="ac-wrap">
                                <input type="text" id="client_text"
                                    class="form-control @error('client_id') is-invalid @enderror"
                                    placeholder="Buscar por nombre o RUT…" autocomplete="off"
                                    value="{{ $oldClient ? $oldClient->name . ' — ' . $oldClient->rut_dni : '' }}">
                                <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">
                            </div>
                            <button type="button" class="btn-add-inline"
                                data-bs-toggle="modal" data-bs-target="#modalNewClient"
                                title="Agregar nuevo cliente">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        @error('client_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vehiculo --}}
                    <div class="mb-3">
                        <label class="form-label">Vehiculo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="ac-wrap">
                                <input type="text" id="vehicle_text"
                                    class="form-control @error('vehicle_id') is-invalid @enderror"
                                    placeholder="Buscar por patente, marca o modelo…" autocomplete="off"
                                    value="{{ $oldVehicle ? $oldVehicle->license_plate . ' — ' . $oldVehicle->brand . ' ' . $oldVehicle->model : '' }}">
                                <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{ old('vehicle_id') }}">
                            </div>
                            <button type="button" id="btnOpenVehicle" class="btn-add-inline"
                                data-bs-toggle="modal" data-bs-target="#modalNewVehicle"
                                title="Agregar nuevo vehiculo">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        @error('vehicle_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div class="mb-3">
                        <label class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>

                    {{-- Numeros --}}
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">N. Siniestro</label>
                            <input type="text" name="claim_number" class="form-control"
                                value="{{ old('claim_number') }}" placeholder="Ej: 85593">
                        </div>
                        <div class="col-6">
                            <label class="form-label">N. Ingreso</label>
                            <input type="text" name="intake_number" class="form-control"
                                value="{{ old('intake_number') }}">
                        </div>
                    </div>
                </div>

                {{-- Card 2: Seguro ── --}}
                <div class="card p-4">
                    <div class="card-section-label">
                        <i class="bi bi-shield-check" style="color:var(--info);font-size:0.85rem;"></i>
                        Seguro y Liquidacion
                    </div>

                    {{-- Compania --}}
                    <div class="mb-3">
                        <label class="form-label">Compania Aseguradora</label>
                        <div class="input-group">
                            <select name="insurance_company_id" id="insurance_company_id" class="form-select">
                                <option value="">Sin seguro</option>
                                @foreach($insuranceCompanies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ old('insurance_company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-add-inline"
                                data-bs-toggle="modal" data-bs-target="#modalNewCompany"
                                title="Agregar nueva compania">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Liquidador --}}
                    <div class="mb-3">
                        <label class="form-label">Liquidador</label>
                        <div class="input-group">
                            <select name="liquidator_id" id="liquidator_id" class="form-select">
                                <option value="">No asignado</option>
                                @foreach($liquidators as $liq)
                                    <option value="{{ $liq->id }}"
                                        {{ old('liquidator_id') == $liq->id ? 'selected' : '' }}>
                                        {{ $liq->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-add-inline"
                                data-bs-toggle="modal" data-bs-target="#modalNewLiquidator"
                                title="Agregar nuevo liquidador">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Deducible --}}
                    <div class="mb-3">
                        <label class="form-label">Deducible (CLP)</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-currency-dollar"></i>
                            <input type="number" name="deductible_amount" class="form-control"
                                value="{{ old('deductible_amount', 0) }}" min="0">
                        </div>
                    </div>

                    {{-- Notas --}}
                    <div class="mb-0">
                        <label class="form-label">Observaciones</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Notas adicionales…">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Card 3: Inventario del Vehiculo ── --}}
                <div class="card p-4">
                    <div class="card-section-label">
                        <i class="bi bi-clipboard-check" style="color:var(--warning);font-size:0.85rem;"></i>
                        Inventario de Ingreso
                    </div>

                    <div class="row g-2 mb-3">
                        @foreach(\App\Models\WorkOrder::INVENTORY_ITEMS as $key => $label)
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="vehicle_inventory[{{ $key }}]" value="1"
                                    id="inv_{{ $key }}" {{ old("vehicle_inventory.{$key}") ? 'checked' : '' }}>
                                <label class="form-check-label" for="inv_{{ $key }}" style="font-size:0.82rem;">
                                    {{ $label }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label" style="font-size:0.78rem;">Combustible</label>
                            <select name="combustible" class="form-select form-select-sm">
                                <option value="">—</option>
                                <option value="E" {{ old('combustible') == 'E' ? 'selected' : '' }}>Vacío</option>
                                <option value="1/4" {{ old('combustible') == '1/4' ? 'selected' : '' }}>1/4</option>
                                <option value="1/2" {{ old('combustible') == '1/2' ? 'selected' : '' }}>1/2</option>
                                <option value="3/4" {{ old('combustible') == '3/4' ? 'selected' : '' }}>3/4</option>
                                <option value="F" {{ old('combustible') == 'F' ? 'selected' : '' }}>Lleno</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label" style="font-size:0.78rem;">KM Ingreso</label>
                            <input type="number" name="km_ingreso" class="form-control form-control-sm"
                                value="{{ old('km_ingreso') }}" min="0" placeholder="0">
                        </div>
                        <div class="col-4">
                            <label class="form-label" style="font-size:0.78rem;">Llaves</label>
                            <input type="number" name="llaves_count" class="form-control form-control-sm"
                                value="{{ old('llaves_count', 1) }}" min="0" max="5">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" style="font-size:0.78rem;">Conductor / Quien entrega</label>
                        <input type="text" name="conductor_name" class="form-control form-control-sm"
                            value="{{ old('conductor_name') }}" placeholder="Nombre del conductor">
                    </div>

                    <div class="mb-0">
                        <label class="form-label" style="font-size:0.78rem;">Declaracion de objetos</label>
                        <textarea name="objects_declaration" class="form-control form-control-sm" rows="2"
                            placeholder="Ej: Sin documentos, sin objetos de valor">{{ old('objects_declaration') }}</textarea>
                    </div>
                </div>

                {{-- Card 4: Etiquetas ── --}}
                <div class="card p-4">
                    <div class="card-section-label">
                        <i class="bi bi-tags-fill" style="color:var(--accent);font-size:0.85rem;"></i>
                        Etiquetas
                    </div>
                    @foreach($tags as $tag)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}">
                        <label class="form-check-label" for="tag_{{ $tag->id }}">
                            <span class="badge" style="background-color: {{ $tag->color }};">{{ $tag->name }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>

            </div>

            {{-- ═══════════════════════════════════════════ --}}
            {{-- Columna Derecha: Tabla de Items            --}}
            {{-- ═══════════════════════════════════════════ --}}
            <div class="col-lg-8">
                <div class="card">

                    {{-- Card header --}}
                    <div class="d-flex justify-content-between align-items-center p-4"
                        style="border-bottom:1px solid var(--border-light);">
                        <div>
                            <h5 class="fw-bold mb-0 ls-tight">Detalle de Trabajos</h5>
                            <p class="mb-0 mt-1" style="font-size:0.775rem;color:var(--text-muted);">
                                Reparaciones, repuestos y mano de obra
                            </p>
                        </div>
                        <button type="button" id="addItem" class="btn-primary-premium" style="padding:0.5rem 1rem;">
                            <i class="bi bi-plus-lg"></i> Agregar linea
                        </button>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table mb-0 items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:150px;">Tipo UN</th>
                                    <th>Descripcion</th>
                                    <th class="text-end" style="width:110px;">Monto Taller</th>
                                    <th class="text-end" style="width:110px;">Monto Autorizado</th>
                                    <th class="text-end" style="width:110px;">Costo Real</th>
                                    <th class="text-center" style="width:72px;">Aprobado</th>
                                    <th class="text-center" style="width:72px;">Salvar</th>
                                    <th style="width:44px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>

                    {{-- Quick add strip --}}
                    <div class="add-row-strip">
                        <button type="button" class="btn-add-row-link" id="addItem2">
                            <i class="bi bi-plus-circle"></i> Agregar otra linea
                        </button>
                    </div>

                    {{-- Totals --}}
                    <div class="d-flex justify-content-end px-4 py-4"
                        style="border-top:1px solid var(--border-light);">
                        <div class="totals-panel">
                            <div class="totals-row">
                                <span>Neto Taller</span>
                                <span id="netoTallerDisplay">$0</span>
                            </div>
                            <div class="totals-row">
                                <span>Neto Autorizado</span>
                                <span id="netoAutorizadoDisplay">$0</span>
                            </div>
                            <div class="totals-row">
                                <span>Neto Costo</span>
                                <span id="netoCostoDisplay">$0</span>
                            </div>
                            <div class="totals-row">
                                <span>IVA (19%)</span>
                                <span id="taxAmount">$0</span>
                            </div>
                            <div class="totals-grand">
                                <span class="fw-700 outfit" style="font-size:0.95rem;color:var(--text-primary);">
                                    Total a Cobrar
                                </span>
                                <span class="fw-800 outfit ls-tight"
                                    style="font-size:1.5rem;color:var(--primary);"
                                    id="totalAmount">$0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 px-4 pb-4"
                        style="border-top:1px solid var(--border-light);padding-top:1rem;">
                        <a href="{{ route('work-orders.index') }}" class="btn-app-secondary">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-primary-premium" style="padding:0.625rem 1.5rem;">
                            <i class="bi bi-check-circle-fill"></i> Guardar OT
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════════ --}}
{{-- MODALES                                                    --}}
{{-- ══════════════════════════════════════════════════════════ --}}

{{-- Modal: Nuevo Cliente --}}
<div class="modal fade" id="modalNewClient" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold mb-0 ls-tight">
                    <i class="bi bi-person-plus-fill me-2" style="color:var(--primary);"></i>Nuevo Cliente
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNewClient">
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">RUT <span class="text-danger">*</span></label>
                        <input type="text" name="rut_dni" class="form-control" required placeholder="12.345.678-9">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefono</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Nuevo Vehiculo --}}
<div class="modal fade" id="modalNewVehicle" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold mb-0 ls-tight">
                    <i class="bi bi-car-front-fill me-2" style="color:var(--primary);"></i>Nuevo Vehiculo
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNewVehicle">
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label">Cliente <span class="text-danger">*</span></label>
                        <div class="ac-wrap">
                            <input type="text" id="nv_client_text" class="form-control"
                                placeholder="Buscar cliente…" autocomplete="off">
                            <input type="hidden" name="client_id" id="nv_client_id" required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Patente <span class="text-danger">*</span></label>
                            <input type="text" name="license_plate" class="form-control text-uppercase" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ano</label>
                            <input type="number" name="year" class="form-control"
                                min="1900" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Marca <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Modelo <span class="text-danger">*</span></label>
                            <input type="text" name="model" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Kilometraje</label>
                            <input type="number" name="odometer" class="form-control" min="0" placeholder="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">N. de Chasis (VIN)</label>
                            <input type="text" name="vin_chassis" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Nueva Compania --}}
<div class="modal fade" id="modalNewCompany" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold mb-0 ls-tight">
                    <i class="bi bi-building-fill me-2" style="color:var(--primary);"></i>Nueva Compania
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNewCompany">
                <div class="modal-body px-4 py-3">
                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Ej: CARDIF" autofocus>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Nuevo Liquidador --}}
<div class="modal fade" id="modalNewLiquidator" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold mb-0 ls-tight">
                    <i class="bi bi-person-badge-fill me-2" style="color:var(--primary);"></i>Nuevo Liquidador
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNewLiquidator">
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Compania <span class="text-danger">*</span></label>
                        <select name="insurance_company_id" id="nv_insurance_company_id" class="form-select" required>
                            <option value="">Seleccione…</option>
                            @foreach($insuranceCompanies as $ic)
                                <option value="{{ $ic->id }}">{{ $ic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Telefono</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const CLP  = v => new Intl.NumberFormat('es-CL', {style:'currency', currency:'CLP', maximumFractionDigits:0}).format(v);
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

// ── Autocomplete ───────────────────────────────────────────────
function initAC({ textId, hiddenId, url, render, label, onSelect }) {
    const txt  = document.getElementById(textId);
    const hid  = document.getElementById(hiddenId);
    const wrap = txt.closest('.ac-wrap');
    const ul   = document.createElement('ul');
    ul.className = 'ac-dropdown';
    wrap.appendChild(ul);
    let timer;

    function doSearch() {
        const q = txt.value.trim();
        if (!q) { ul.style.display = 'none'; return; }
        clearTimeout(timer);
        timer = setTimeout(() => {
            const base = typeof url === 'function' ? url() : url;
            fetch(`${base}${base.includes('?') ? '&' : '?'}q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(items => {
                ul.innerHTML = '';
                if (!items.length) { ul.style.display = 'none'; return; }
                items.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML  = render(item);
                    li.dataset.lbl = label(item);
                    li.dataset.id  = item.id;
                    li.addEventListener('mousedown', e => {
                        e.preventDefault();
                        txt.value = li.dataset.lbl;
                        hid.value = li.dataset.id;
                        ul.style.display = 'none';
                        txt.classList.remove('is-invalid');
                        if (onSelect) onSelect(item);
                    });
                    ul.appendChild(li);
                });
                ul.style.display = 'block';
            })
            .catch(() => ul.style.display = 'none');
        }, 220);
    }

    txt.addEventListener('input', () => { hid.value = ''; doSearch(); });
    txt.addEventListener('blur',  () => setTimeout(() => ul.style.display = 'none', 180));
    txt.addEventListener('keydown', e => {
        const items  = [...ul.querySelectorAll('li')];
        const active = ul.querySelector('li.ac-active');
        const idx    = active ? items.indexOf(active) : -1;
        if (e.key === 'ArrowDown')  { e.preventDefault(); active?.classList.remove('ac-active'); (items[idx + 1] ?? items[0])?.classList.add('ac-active'); }
        if (e.key === 'ArrowUp')    { e.preventDefault(); active?.classList.remove('ac-active'); (items[idx - 1] ?? items[items.length - 1])?.classList.add('ac-active'); }
        if (e.key === 'Enter' && active) { e.preventDefault(); active.dispatchEvent(new MouseEvent('mousedown')); }
        if (e.key === 'Escape')     { ul.style.display = 'none'; }
    });

    return {
        setValue: (id, lbl) => { hid.value = id; txt.value = lbl; }
    };
}

// ── Iniciar autocomplete cliente ───────────────────────────────
const acClient = initAC({
    textId:   'client_text',
    hiddenId: 'client_id',
    url:      '{{ route("clients.search") }}',
    render:   c => `<strong>${c.name}</strong> <span style="color:#888;font-size:.8rem;">${c.rut_dni ?? ''}</span>`,
    label:    c => c.name + (c.rut_dni ? ' — ' + c.rut_dni : ''),
    onSelect: c => {
        // Limpiar vehiculo al cambiar cliente
        document.getElementById('vehicle_text').value = '';
        document.getElementById('vehicle_id').value   = '';
        selectedClientId = c.id;
    }
});

// ── Iniciar autocomplete vehiculo ──────────────────────────────
let selectedClientId = document.getElementById('client_id').value || null;

const acVehicle = initAC({
    textId:   'vehicle_text',
    hiddenId: 'vehicle_id',
    url:      () => '{{ route("vehicles.search") }}' + (selectedClientId ? `?client_id=${selectedClientId}` : ''),
    render:   v => `<strong>${v.license_plate}</strong> <span style="color:#888;font-size:.8rem;">${v.brand} ${v.model}</span>`,
    label:    v => `${v.license_plate} — ${v.brand} ${v.model}`,
});

// ── Autocomplete cliente en modal "Nuevo Vehiculo" ─────────────
initAC({
    textId:   'nv_client_text',
    hiddenId: 'nv_client_id',
    url:      '{{ route("clients.search") }}',
    render:   c => `<strong>${c.name}</strong> <span style="color:#888;font-size:.8rem;">${c.rut_dni ?? ''}</span>`,
    label:    c => c.name + (c.rut_dni ? ' — ' + c.rut_dni : ''),
});

// Pre-llenar cliente en modal vehiculo al abrirlo
document.getElementById('btnOpenVehicle').addEventListener('click', () => {
    const cid  = document.getElementById('client_id').value;
    const clbl = document.getElementById('client_text').value;
    if (cid) {
        document.getElementById('nv_client_id').value   = cid;
        document.getElementById('nv_client_text').value = clbl;
    }
});

// ── Validacion antes de submit ─────────────────────────────────
document.getElementById('workOrderForm').addEventListener('submit', function (e) {
    let ok = true;
    const clientTxt = document.getElementById('client_text');
    const vehicleTxt = document.getElementById('vehicle_text');
    if (!document.getElementById('client_id').value) {
        clientTxt.classList.add('is-invalid'); clientTxt.focus(); ok = false;
    }
    if (!document.getElementById('vehicle_id').value) {
        vehicleTxt.classList.add('is-invalid'); if (ok) vehicleTxt.focus(); ok = false;
    }
    if (!ok) e.preventDefault();
});

// ── Tabla de items ─────────────────────────────────────────────
const UN_TYPES = @json($unTypes->map(fn($u) => ['id' => $u->id, 'code' => $u->code, 'name' => $u->name]));

function buildUnOptions(selectedId = null) {
    return UN_TYPES.map(u =>
        `<option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>${u.code} — ${u.name}</option>`
    ).join('');
}

// ── Autocomplete para descripcion de items (catalogo servicios) ──
function initDescAC(input) {
    const wrap = input.closest('.ac-wrap');
    const ul   = document.createElement('ul');
    ul.className = 'ac-dropdown';
    wrap.appendChild(ul);
    let timer;

    input.addEventListener('input', () => {
        const q = input.value.trim();
        if (q.length < 2) { ul.style.display = 'none'; return; }
        clearTimeout(timer);
        timer = setTimeout(() => {
            fetch(`{{ route('parts.search') }}?q=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(items => {
                ul.innerHTML = '';
                if (!items.length) { ul.style.display = 'none'; return; }
                items.forEach(si => {
                    const li = document.createElement('li');
                    li.innerHTML = `${si.name} <span class="text-muted" style="font-size:.75rem">${si.category}</span>`;
                    li.addEventListener('mousedown', e => {
                        e.preventDefault();
                        input.value = si.name;
                        ul.style.display = 'none';
                        recalc();
                    });
                    ul.appendChild(li);
                });
                ul.style.display = 'block';
            })
            .catch(() => ul.style.display = 'none');
        }, 250);
    });

    input.addEventListener('blur', () => setTimeout(() => ul.style.display = 'none', 180));
    input.addEventListener('keydown', e => {
        const lis    = [...ul.querySelectorAll('li')];
        const active = ul.querySelector('li.ac-active');
        const idx    = active ? lis.indexOf(active) : -1;
        if (e.key === 'ArrowDown')  { e.preventDefault(); active?.classList.remove('ac-active'); (lis[idx + 1] ?? lis[0])?.classList.add('ac-active'); }
        if (e.key === 'ArrowUp')    { e.preventDefault(); active?.classList.remove('ac-active'); (lis[idx - 1] ?? lis[lis.length - 1])?.classList.add('ac-active'); }
        if (e.key === 'Enter' && active) { e.preventDefault(); active.dispatchEvent(new MouseEvent('mousedown')); }
        if (e.key === 'Escape')     { ul.style.display = 'none'; }
    });
}

let rowIdx = 0;

function addRow(data = {}) {
    const i            = rowIdx++;
    const unId         = data.un_type_id || (UN_TYPES[0]?.id ?? '');
    const desc         = (data.description || '').replace(/"/g, '&quot;');
    const priceWork    = data.price_workshop ?? '';
    const priceAuth    = data.price_authorized ?? '';
    const priceReal    = data.price_real ?? '';
    const approved     = data.is_approved !== undefined ? (data.is_approved ? 'checked' : '') : 'checked';
    const salvage      = data.is_salvage ? 'checked' : '';

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select name="items[${i}][un_type_id]" class="form-select form-select-sm un-sel fw-bold" required>
                ${buildUnOptions(unId)}
            </select>
        </td>
        <td style="position:relative;">
            <div class="ac-wrap">
                <input type="text" name="items[${i}][description]" class="form-control form-control-sm desc-input"
                    value="${desc}" placeholder="Descripcion del trabajo o repuesto…" required autocomplete="off">
            </div>
        </td>
        <td>
            <input type="number" name="items[${i}][price_workshop]" class="form-control form-control-sm price-inp price-workshop"
                value="${priceWork}" min="0" step="1" placeholder="0">
        </td>
        <td>
            <input type="number" name="items[${i}][price_authorized]" class="form-control form-control-sm price-inp price-authorized"
                value="${priceAuth}" min="0" step="1" placeholder="0">
        </td>
        <td>
            <input type="number" name="items[${i}][price_real]" class="form-control form-control-sm price-inp price-real"
                value="${priceReal}" min="0" step="1" placeholder="0">
        </td>
        <td class="text-center">
            <input type="checkbox" name="items[${i}][is_approved]" value="1" class="form-check-input chk-approved" ${approved}
                title="Marcar como aprobado">
        </td>
        <td class="text-center">
            <input type="checkbox" name="items[${i}][is_salvage]" value="1" class="form-check-input" ${salvage}
                title="Marcar como salvage">
        </td>
        <td>
            <button type="button" class="rm-row" title="Eliminar linea">
                <i class="bi bi-trash3"></i>
            </button>
        </td>
    `;

    tr.querySelector('.price-workshop').addEventListener('input', recalc);
    tr.querySelector('.price-authorized').addEventListener('input', recalc);
    tr.querySelector('.price-real').addEventListener('input', recalc);
    tr.querySelector('.chk-approved').addEventListener('change', recalc);
    tr.querySelector('.rm-row').addEventListener('click', () => { tr.remove(); recalc(); });
    document.getElementById('itemsBody').appendChild(tr);
    initDescAC(tr.querySelector('.desc-input'));
    recalc();
    tr.querySelector('.desc-input').focus();
}

function recalc() {
    let netoTaller = 0, netoAutorizado = 0, netoCosto = 0;
    const rows = document.querySelectorAll('#itemsBody tr');

    rows.forEach(row => {
        const workshop  = parseFloat(row.querySelector('.price-workshop')?.value) || 0;
        const authorized = parseFloat(row.querySelector('.price-authorized')?.value) || 0;
        const real      = parseFloat(row.querySelector('.price-real')?.value) || 0;
        const isApproved = row.querySelector('.chk-approved')?.checked;

        netoTaller += workshop;
        netoCosto  += real;
        if (isApproved) {
            netoAutorizado += authorized;
        }
    });

    const iva   = Math.round(netoAutorizado * 0.19);
    const total = netoAutorizado + iva;

    document.getElementById('netoTallerDisplay').textContent     = CLP(netoTaller);
    document.getElementById('netoAutorizadoDisplay').textContent = CLP(netoAutorizado);
    document.getElementById('netoCostoDisplay').textContent      = CLP(netoCosto);
    document.getElementById('taxAmount').textContent             = CLP(iva);
    document.getElementById('totalAmount').textContent           = CLP(total);
}

document.getElementById('addItem').addEventListener('click',  () => addRow());
document.getElementById('addItem2').addEventListener('click', () => addRow());
addRow();

// ── Creacion rapida via AJAX ───────────────────────────────────
function quickPost(url, data, onSuccess) {
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(json => {
        if (json.errors) { alert('Error:\n' + Object.values(json.errors).flat().join('\n')); }
        else { onSuccess(json); }
    })
    .catch(() => alert('Error al conectar con el servidor.'));
}

function addOption(selectId, id, text) {
    const sel = document.getElementById(selectId);
    const opt = new Option(text, id, true, true);
    sel.appendChild(opt);
    sel.value = id;
}

function closeModal(modalId) {
    bootstrap.Modal.getInstance(document.getElementById(modalId))?.hide();
}

// ── Cliente creado desde modal
document.getElementById('formNewClient').addEventListener('submit', function (e) {
    e.preventDefault();
    quickPost('{{ route("clients.quickStore") }}', Object.fromEntries(new FormData(this)), json => {
        const lbl = json.name + (json.rut_dni ? ' — ' + json.rut_dni : '');
        acClient.setValue(json.id, lbl);
        selectedClientId = json.id;
        document.getElementById('vehicle_text').value = '';
        document.getElementById('vehicle_id').value   = '';
        this.reset();
        closeModal('modalNewClient');
    });
});

// ── Vehiculo creado desde modal
document.getElementById('formNewVehicle').addEventListener('submit', function (e) {
    e.preventDefault();
    quickPost('{{ route("vehicles.quickStore") }}', Object.fromEntries(new FormData(this)), json => {
        acVehicle.setValue(json.id, json.label);
        this.reset();
        closeModal('modalNewVehicle');
    });
});

// ── Compania
document.getElementById('formNewCompany').addEventListener('submit', function (e) {
    e.preventDefault();
    quickPost('{{ route("insurance-companies.quickStore") }}', Object.fromEntries(new FormData(this)), json => {
        addOption('insurance_company_id', json.id, json.name);
        const liqSel = document.getElementById('nv_insurance_company_id');
        if (liqSel) liqSel.appendChild(new Option(json.name, json.id));
        this.reset();
        closeModal('modalNewCompany');
    });
});

// ── Liquidador
document.getElementById('formNewLiquidator').addEventListener('submit', function (e) {
    e.preventDefault();
    quickPost('{{ route("liquidators.quickStore") }}', Object.fromEntries(new FormData(this)), json => {
        addOption('liquidator_id', json.id, json.name);
        this.reset();
        closeModal('modalNewLiquidator');
    });
});
</script>
@endsection

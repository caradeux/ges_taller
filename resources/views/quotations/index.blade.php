@extends('layouts.app')

@section('title', 'Presupuestos')

@section('content')
    <div class="animate-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Presupuestos / Cotizaciones</h2>
                <p class="text-secondary small mb-0">Gestión de cotizaciones para clientes y compañías de seguros.</p>
            </div>
            <a href="{{ route('quotations.create') }}" class="btn-primary-premium">
                <i class="bi bi-plus-lg"></i> Nuevo Presupuesto
            </a>
        </div>

        <div class="card">
            <div class="p-4 border-bottom bg-light" style="border-radius: 1rem 1rem 0 0;">
                <form action="{{ route('quotations.index') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-secondary"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Buscar por folio, cliente, patente o aseguradora..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Todos los estados</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Enviado / Pendiente
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                            <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Terminado
                            </option>
                            <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>Facturado
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary w-100">Filtrar</button>
                        @if(request()->anyFilled(['search', 'status']))
                            <a href="{{ route('quotations.index') }}" class="btn btn-outline-secondary" title="Limpiar Filtros">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotations as $q)
                            <tr>
                                <td><span class="fw-bold text-dark">#{{ $q->folio }}</span></td>
                                <td class="fw-medium">{{ $q->client->name }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold small">{{ $q->vehicle->license_plate }}</span>
                                        <span class="tiny text-secondary" style="font-size: 0.7rem;">{{ $q->vehicle->brand }}
                                            {{ $q->vehicle->model }}</span>
                                    </div>
                                </td>
                                <td class="text-secondary small">{{ \Carbon\Carbon::parse($q->date)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.65rem; 
                                                @if($q->status == 'draft') background-color: #fef3c7; color: #92400e;
                                                @elseif($q->status == 'approved') background-color: #dcfce7; color: #166534;
                                                @elseif($q->status == 'sent') background-color: #e0f2fe; color: #075985;
                                                @elseif($q->status == 'rejected') background-color: #fee2e2; color: #991b1b;
                                                @else background-color: #f1f5f9; color: #475569; @endif">
                                        {{ $q->status_label }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    ${{ number_format($q->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm"
                                            style="border-radius: 0.75rem;">
                                            <li><a class="dropdown-item" href="{{ route('quotations.show', $q) }}"><i
                                                        class="bi bi-eye me-2"></i> Ver Detalle</a></li>
                                            @if(!in_array($q->status, ['invoiced', 'rejected']))
                                                <li><a class="dropdown-item" href="{{ route('quotations.edit', $q) }}"><i
                                                            class="bi bi-pencil me-2"></i> Editar</a></li>
                                            @endif
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('quotations.destroy', $q) }}" method="POST"
                                                    onsubmit="return confirm('¿Eliminar el presupuesto #{{ $q->folio }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> Eliminar
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-receipt fs-1 text-light"></i>
                                    <p class="text-secondary mt-2">No se encontraron presupuestos registrados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($quotations->hasPages())
                <div class="p-3 border-top bg-light" style="border-radius: 0 0 1rem 1rem;">
                    {{ $quotations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
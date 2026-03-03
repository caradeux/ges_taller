@extends('layouts.app')

@section('title', 'Detalle de Presupuesto #' . $quotation->folio)

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4 animate__animated animate__fadeIn">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-white fw-bold">Presupuesto #{{ $quotation->folio }}</h1>
                    <p class="text-secondary mb-0">Detalle completo de la cotización.</p>
                </div>
                <div class="d-flex gap-2">
                    @if($quotation->status == 'draft')
                        <form action="{{ route('quotations.status', $quotation) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="sent">
                            <button type="submit" class="btn btn-info btn-sm">
                                <i class="bi bi-send"></i> Marcar como Enviado
                            </button>
                        </form>
                    @elseif($quotation->status == 'sent')
                        <form action="{{ route('quotations.status', $quotation) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-lg"></i> Aprobar
                            </button>
                        </form>
                        <form action="{{ route('quotations.status', $quotation) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-x-lg"></i> Rechazar
                            </button>
                        </form>
                    @elseif($quotation->status == 'approved')
                        <form action="{{ route('quotations.status', $quotation) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="finished">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-flag"></i> Terminar Reparación
                            </button>
                        </form>
                    @elseif($quotation->status == 'finished')
                        <form action="{{ route('quotations.status', $quotation) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="invoiced">
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="bi bi-receipt"></i> Facturar
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('quotations.pdf', $quotation) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark border-secondary border-opacity-25 h-100">
                    <div class="card-header border-secondary border-opacity-25 bg-secondary bg-opacity-10">
                        <h5 class="card-title mb-0 text-white small fw-bold">INFORMACIÓN DEL CLIENTE</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 text-secondary small">Nombre</p>
                        <p class="text-white fw-bold">{{ $quotation->client->name }}</p>
                        <p class="mb-1 text-secondary small">RUT</p>
                        <p class="text-white fw-bold">{{ $quotation->client->rut_dni }}</p>
                        <p class="mb-1 text-secondary small">Teléfono</p>
                        <p class="text-white fw-bold">{{ $quotation->client->phone }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark border-secondary border-opacity-25 h-100">
                    <div class="card-header border-secondary border-opacity-25 bg-secondary bg-opacity-10">
                        <h5 class="card-title mb-0 text-white small fw-bold">INFORMACIÓN DEL VEHÍCULO</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 text-secondary small">Patente</p>
                        <p class="text-white fw-bold">{{ $quotation->vehicle->license_plate }}</p>
                        <p class="mb-1 text-secondary small">Marca / Modelo</p>
                        <p class="text-white fw-bold">{{ $quotation->vehicle->brand }} {{ $quotation->vehicle->model }}</p>
                        <p class="mb-1 text-secondary small">Kilometraje</p>
                        <p class="text-white fw-bold text-info">
                            {{ number_format($quotation->vehicle->odometer, 0, ',', '.') }} km</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark border-secondary border-opacity-25 h-100">
                    <div class="card-header border-secondary border-opacity-25 bg-secondary bg-opacity-10">
                        <h5 class="card-title mb-0 text-white small fw-bold">DATOS DEL SEGURO</h5>
                    </div>
                    <div class="card-body">
                        @if($quotation->insurance_company_id)
                            <p class="mb-1 text-secondary small">Compañía</p>
                            <p class="text-white fw-bold">{{ $quotation->insuranceCompany->name }}</p>
                            <p class="mb-1 text-secondary small">Liquidador</p>
                            <p class="text-white fw-bold">
                                {{ $quotation->liquidator ? $quotation->liquidator->name : 'No asignado' }}</p>
                        @else
                            <p class="text-secondary text-center py-4">Particular (Sin Seguro)</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-dark border-secondary border-opacity-25 mb-4">
            <div
                class="card-header border-secondary border-opacity-25 bg-secondary bg-opacity-10 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-white small fw-bold">DETALLE DE TRABAJOS Y RESPUESTOS</h5>
                <span class="badge bg-{{ $quotation->status_color }}-soft">{{ $quotation->status_label }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Descripción</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotation->items as $item)
                                <tr>
                                    <td class="ps-4">{{ $item->description }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $item->type == 'repuesto' ? 'bg-primary' : 'bg-info' }} bg-opacity-10 text-{{ $item->type == 'repuesto' ? 'primary' : 'info' }}">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end text-secondary">${{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end pe-4 fw-bold text-white">
                                        ${{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-secondary border-opacity-25 bg-black bg-opacity-25 p-4">
                <div class="row">
                    <div class="col-md-6 text-secondary small">
                        <strong>Observaciones:</strong><br>
                        {{ $quotation->notes ?? 'Sin observaciones.' }}
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-5">
                            <div class="text-end">
                                <p class="mb-0 text-secondary small text-uppercase fw-bold">Subtotal Neto</p>
                                <h5 class="text-white">${{ number_format($quotation->total_amount / 1.19, 0, ',', '.') }}
                                </h5>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 text-secondary small text-uppercase fw-bold">IVA (19%)</p>
                                <h5 class="text-white">
                                    ${{ number_format($quotation->total_amount - ($quotation->total_amount / 1.19), 0, ',', '.') }}
                                </h5>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 text-secondary small text-uppercase fw-bold">Total</p>
                                <h3 class="text-primary fw-bold">${{ number_format($quotation->total_amount, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
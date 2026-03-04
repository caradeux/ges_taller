@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="animate-in">

    {{-- ─── Header ─────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Clientes</h2>
            <p class="page-subtitle">Administra la base de datos de clientes y sus datos de contacto.</p>
        </div>
        <a href="{{ route('clients.create') }}" class="btn-primary-premium">
            <i class="bi bi-person-plus-fill"></i> Nuevo Cliente
        </a>
    </div>

    <div class="card">

        {{-- ─── Filter Bar ─────────────────────────────────── --}}
        <div class="filter-bar">
            <form action="{{ route('clients.index') }}" method="GET"
                class="d-flex gap-3 align-items-center flex-wrap">
                <div class="input-icon-wrap flex-grow-1" style="max-width:400px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control"
                        placeholder="Buscar por nombre, RUT, email o teléfono…"
                        value="{{ request('search') }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-premium" style="padding:0.5625rem 1rem;">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('clients.index') }}" class="btn-app-secondary" title="Limpiar búsqueda">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ─── Table ───────────────────────────────────────── --}}
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>RUT / DNI</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th class="text-center">Vehículos</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>
                                <span class="fw-700 text-sm ls-tight"
                                    style="font-weight:700;color:var(--text-primary);font-variant-numeric:tabular-nums;">
                                    {{ $client->rut_dni }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('clients.show', $client) }}"
                                    class="fw-500 text-decoration-none"
                                    style="font-weight:500;color:var(--text-primary);">
                                    {{ $client->name }}
                                </a>
                            </td>
                            <td class="text-sm" style="color:var(--text-secondary);">
                                {{ $client->email ?? '—' }}
                            </td>
                            <td class="text-sm" style="color:var(--text-secondary);">
                                {{ $client->phone ?? '—' }}
                            </td>
                            <td class="text-center">
                                @php $vCount = $client->vehicles->count(); @endphp
                                <span class="status-badge"
                                    style="background:var(--primary-light);color:var(--primary);">
                                    <i class="bi bi-car-front me-1"></i>{{ $vCount }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm border-0 bg-transparent"
                                        style="color:var(--text-muted);"
                                        type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('clients.show', $client) }}">
                                                <i class="bi bi-eye me-2"></i> Ver Ficha
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('clients.edit', $client) }}">
                                                <i class="bi bi-pencil me-2"></i> Editar
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                                @csrf @method('DELETE')
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
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <p>No hay clientes registrados aún.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ─── Pagination ───────────────────────────────────── --}}
        @if($clients->hasPages())
            <div class="table-footer">
                {{ $clients->links() }}
            </div>
        @endif

    </div>
</div>
@endsection

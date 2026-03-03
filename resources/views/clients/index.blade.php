@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
    <div class="animate-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Clientes</h2>
                <p class="text-secondary small mb-0">Administra la base de datos de tus clientes y sus datos de contacto.
                </p>
            </div>
            <a href="{{ route('clients.create') }}" class="btn-primary-premium">
                <i class="bi bi-person-plus-fill"></i> Nuevo Cliente
            </a>
        </div>

        <div class="card">
            <div class="p-4 border-bottom bg-light d-flex gap-3 align-items-center"
                style="border-radius: 1rem 1rem 0 0;">
                <form action="{{ route('clients.index') }}" method="GET" class="d-flex gap-3 w-100 align-items-center">
                    <div class="input-group" style="max-width: 380px;">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="bi bi-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Buscar por nombre, RUT, email o teléfono..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm px-3">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm"
                            title="Limpiar">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>RUT / DNI</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Vehículos</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td><span class="fw-bold text-dark">{{ $client->rut_dni }}</span></td>
                                <td class="fw-medium">{{ $client->name }}</td>
                                <td class="text-secondary small">{{ $client->email ?? 'N/A' }}</td>
                                <td class="text-secondary small">{{ $client->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-2">
                                        <i class="bi bi-car-front"></i> {{ $client->vehicles->count() }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm"
                                            style="border-radius: 0.75rem;">
                                            <li><a class="dropdown-item" href="{{ route('clients.show', $client) }}"><i
                                                        class="bi bi-eye me-2"></i> Ver Ficha</a></li>
                                            <li><a class="dropdown-item" href="{{ route('clients.edit', $client) }}"><i
                                                        class="bi bi-pencil me-2"></i> Editar</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i
                                                            class="bi bi-trash me-2"></i> Eliminar</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-people fs-1 text-light"></i>
                                    <p class="text-secondary mt-2">No hay clientes registrados aún.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($clients->hasPages())
                <div class="p-3 border-top bg-light" style="border-radius: 0 0 1rem 1rem;">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
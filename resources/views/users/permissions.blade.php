@extends('layouts.app')

@section('title', 'Permisos por Rol')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('users.index') }}" class="text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Volver a Usuarios
            </a>
            <h2 class="outfit fw-bold mt-2 mb-1">Permisos por Rol</h2>
            <p class="text-muted mb-0">Qué secciones puede acceder cada rol del sistema</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:40%">Sección / Acción</th>
                        <th class="text-center">
                            <span class="badge bg-primary px-3 py-2">Administrador</span>
                        </th>
                        <th class="text-center">
                            <span class="badge bg-info text-dark px-3 py-2">Recepción</span>
                        </th>
                        <th class="text-center">
                            <span class="badge bg-warning text-dark px-3 py-2">Taller</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section => $actions)
                    <tr class="table-light">
                        <td colspan="4" class="fw-bold text-uppercase" style="font-size:0.7rem;letter-spacing:0.08em;color:#64748b;">
                            {{ $section }}
                        </td>
                    </tr>
                    @foreach($actions as $label => $roles)
                    <tr>
                        <td class="ps-4">{{ $label }}</td>
                        <td class="text-center">
                            @if(in_array('admin', $roles))
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @else
                                <i class="bi bi-x-circle text-muted fs-5 opacity-25"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(in_array('recepcion', $roles))
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @else
                                <i class="bi bi-x-circle text-muted fs-5 opacity-25"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(in_array('taller', $roles))
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @else
                                <i class="bi bi-x-circle text-muted fs-5 opacity-25"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="alert border-0 mt-4" style="background:#f0f9ff;border-radius:1rem;">
        <i class="bi bi-info-circle-fill text-info me-2"></i>
        <strong>Nota:</strong> Para modificar los permisos de cada rol, edita el archivo
        <code>config/permissions.php</code>. Los cambios se aplican de inmediato.
    </div>
</div>
@endsection

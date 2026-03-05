@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
    <div class="animate-in">
        <div class="mb-4">
            <h2 class="fw-bold mb-1">Configuración de Perfil</h2>
            <p class="text-secondary small mb-0">Administra tu información personal y seguridad de la cuenta.</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                {{-- Company info (admin only) --}}
                @if(auth()->user()->role === 'admin')
                <div class="card p-4 mb-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-building-fill me-2 text-primary"></i>Datos del Taller
                    </h5>
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        {{-- Hidden user fields to keep them valid --}}
                        <input type="hidden" name="name"  value="{{ auth()->user()->name }}">
                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Razón Social</label>
                                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                                    value="{{ old('company_name', $company->name) }}" required>
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">RUT Empresa</label>
                                <input type="text" name="company_rut" class="form-control"
                                    value="{{ old('company_rut', $company->rut) }}" placeholder="Ej: 77.604.871-2">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Dirección</label>
                                <input type="text" name="company_address" class="form-control"
                                    value="{{ old('company_address', $company->address) }}" placeholder="Ej: Juan Enrique Lira 3580, Viña del Mar">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Teléfono</label>
                                <input type="text" name="company_phone" class="form-control"
                                    value="{{ old('company_phone', $company->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Email Taller</label>
                                <input type="email" name="company_email" class="form-control"
                                    value="{{ old('company_email', $company->email) }}">
                            </div>

                            <div class="col-12"><hr class="my-1"></div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">
                                    Vigencia de Cotizaciones
                                    <i class="bi bi-info-circle text-muted ms-1"
                                        title="Días de validez que se muestra en cada cotización generada"
                                        data-bs-toggle="tooltip"></i>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="quotation_validity_days" class="form-control @error('quotation_validity_days') is-invalid @enderror"
                                        value="{{ old('quotation_validity_days', $company->quotation_validity_days ?? 30) }}"
                                        min="1" max="365" required>
                                    <span class="input-group-text text-secondary">días</span>
                                </div>
                                @error('quotation_validity_days')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                <div class="form-text">Entre 1 y 365 días (por defecto 30).</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">
                                    Próximo Nº Cotización
                                    <i class="bi bi-info-circle text-muted ms-1"
                                        title="Correlativo desde el cual se numerarán las nuevas cotizaciones"
                                        data-bs-toggle="tooltip"></i>
                                </label>
                                <input type="number" name="folio_counter" class="form-control @error('folio_counter') is-invalid @enderror"
                                    value="{{ old('folio_counter', $company->folio_counter ?? 1) }}"
                                    min="1" required>
                                @error('folio_counter')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                <div class="form-text">La siguiente cotización usará este número.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Logo del Taller</label>
                                @if($company->logo_path)
                                <div class="mb-2">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($company->logo_path) }}" alt="Logo" style="max-height:80px; max-width:220px; object-fit:contain; border:1px solid #dee2e6; border-radius:6px; padding:6px; background:#fff;">
                                    <div class="form-text">Logo actual. Sube uno nuevo para reemplazarlo.</div>
                                </div>
                                @else
                                <div class="form-text mb-1">Sin logo cargado.</div>
                                @endif
                                <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/*">
                                @error('company_logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">PNG, JPG o SVG. Máx. 2 MB. Se usará en el PDF de cotizaciones.</div>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn-primary-premium">
                                    <i class="bi bi-building-fill"></i> Guardar Datos del Taller
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <div class="card p-4 mb-4">
                    <h5 class="fw-bold mb-4">Información Personal</h5>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nombre Completo</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4 text-light">

                            <h5 class="fw-bold mb-3">Cambiar Contraseña</h5>
                            <p class="text-secondary small mb-3">Deja en blanco si no deseas cambiar la contraseña.</p>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Contraseña Actual</label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nueva Contraseña</label>
                                <input type="password" name="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Confirmar Nueva Contraseña</label>
                                <input type="password" name="new_password_confirmation" class="form-control">
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn-primary-premium">
                                    <i class="bi bi-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center fw-bold text-white mx-auto"
                            style="width:96px;height:96px;font-size:2.5rem;background:linear-gradient(135deg,#2563eb 0%,#3b82f6 100%);">
                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-secondary small mb-3">{{ $user->email }}</p>
                    @php
                        $roleName = ['admin'=>'Administrador','recepcion'=>'Recepción','taller'=>'Taller'][$user->role] ?? $user->role;
                        $roleBadge = ['admin'=>'bg-primary','recepcion'=>'bg-info text-dark','taller'=>'bg-warning text-dark'][$user->role] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $roleBadge }} px-3 py-2 rounded-pill">{{ $roleName }}</span>

                    <hr class="my-4 text-light">

                    <div class="text-start">
                        <h6 class="fw-bold small text-uppercase text-secondary mb-3">Resumen de Actividad</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-secondary">Miembro desde</span>
                            <span class="small fw-bold">{{ $user->created_at->format('d M, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="small text-secondary">Última actualización</span>
                            <span class="small fw-bold">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="animate-in" style="max-width:600px;">
    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="text-muted text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Volver a Usuarios
        </a>
        <h2 class="outfit fw-bold mt-2 mb-0">Editar Usuario</h2>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre completo</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Rol</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="admin"     {{ old('role', $user->role) === 'admin'     ? 'selected' : '' }}>Administrador</option>
                        <option value="recepcion" {{ old('role', $user->role) === 'recepcion' ? 'selected' : '' }}>Recepción</option>
                        <option value="taller"    {{ old('role', $user->role) === 'taller'    ? 'selected' : '' }}>Taller</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sucursal</label>
                    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                        <option value="">Sin sucursal asignada</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4">
                <p class="text-muted small mb-3">Dejar en blanco para mantener la contraseña actual.</p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nueva contraseña</label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirmar nueva contraseña</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

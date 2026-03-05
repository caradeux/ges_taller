@extends('layouts.app')

@section('title', 'Editar Vehículo')

@section('styles')
<style>
    .ac-wrap { position: relative; }
    .ac-dropdown {
        display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 9999;
        background: #fff; border: 1px solid #dee2e6;
        border-radius: 0 0 .375rem .375rem;
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
        max-height: 200px; overflow-y: auto; list-style: none; padding: 0; margin: 0;
    }
    .ac-dropdown li { padding: .45rem .75rem; cursor: pointer; font-size: .84rem; border-bottom: 1px solid #f0f0f0; }
    .ac-dropdown li:last-child { border-bottom: none; }
    .ac-dropdown li:hover, .ac-dropdown li.ac-active { background: #f0f4ff; }
</style>
@endsection

@section('content')
    <div class="animate-in" style="max-width: 800px; margin: 0 auto;">
        <div class="mb-4">
            <a href="{{ route('vehicles.index') }}" class="text-decoration-none text-secondary small fw-medium">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
            <h2 class="fw-bold mt-2">Editar Vehículo</h2>
            <p class="text-secondary small">Modifica los datos del vehículo
                <strong>{{ strtoupper($vehicle->license_plate) }}</strong>.</p>
        </div>

        <div class="card p-4">
            <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Dueño (Cliente) <span
                                class="text-danger">*</span></label>
                        <div class="ac-wrap">
                            <input type="text" id="client_text"
                                class="form-control @error('client_id') is-invalid @enderror"
                                autocomplete="off"
                                value="{{ $vehicle->client ? $vehicle->client->name . ' — ' . $vehicle->client->rut_dni : '' }}">
                            <input type="hidden" name="client_id" id="client_id"
                                value="{{ old('client_id', $vehicle->client_id) }}">
                        </div>
                        @error('client_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Patente <span class="text-danger">*</span></label>
                        <input type="text" name="license_plate"
                            class="form-control @error('license_plate') is-invalid @enderror"
                            value="{{ old('license_plate', $vehicle->license_plate) }}" placeholder="Ej: AB CD 12 o ABCD12"
                            required>
                        @error('license_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Marca <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                            value="{{ old('brand', $vehicle->brand) }}" placeholder="Ej: Toyota" required>
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Modelo <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                            value="{{ old('model', $vehicle->model) }}" placeholder="Ej: Hilux" required>
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Año</label>
                        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                            value="{{ old('year', $vehicle->year) }}" min="1950" max="{{ date('Y') + 1 }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Color</label>
                        <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
                            value="{{ old('color', $vehicle->color) }}" placeholder="Ej: Gris Metálico">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">KM Actual</label>
                        <input type="number" name="odometer" class="form-control @error('odometer') is-invalid @enderror"
                            value="{{ old('odometer', $vehicle->odometer) }}" min="0">
                        @error('odometer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 pt-3 border-top mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('vehicles.index') }}"
                            class="btn btn-light px-4 rounded-pill fw-medium">Cancelar</a>
                        <button type="submit" class="btn-primary-premium">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@section('scripts')
<script>
(function () {
    const txt  = document.getElementById('client_text');
    const hid  = document.getElementById('client_id');
    const wrap = txt.closest('.ac-wrap');
    const ul   = document.createElement('ul');
    ul.className = 'ac-dropdown';
    wrap.appendChild(ul);
    let timer;

    txt.addEventListener('input', () => {
        hid.value = '';
        const q = txt.value.trim();
        if (!q) { ul.style.display = 'none'; return; }
        clearTimeout(timer);
        timer = setTimeout(() => {
            fetch(`{{ route('clients.search') }}?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(items => {
                ul.innerHTML = '';
                if (!items.length) { ul.style.display = 'none'; return; }
                items.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML   = `<strong>${item.name}</strong> <span style="color:#888;font-size:.8rem;">${item.rut_dni ?? ''}</span>`;
                    li.dataset.lbl = item.name + (item.rut_dni ? ' — ' + item.rut_dni : '');
                    li.dataset.id  = item.id;
                    li.addEventListener('mousedown', e => {
                        e.preventDefault();
                        txt.value = li.dataset.lbl;
                        hid.value = li.dataset.id;
                        ul.style.display = 'none';
                    });
                    ul.appendChild(li);
                });
                ul.style.display = 'block';
            })
            .catch(() => ul.style.display = 'none');
        }, 220);
    });

    txt.addEventListener('blur',    () => setTimeout(() => ul.style.display = 'none', 180));
    txt.addEventListener('keydown', e => {
        const items  = [...ul.querySelectorAll('li')];
        const active = ul.querySelector('li.ac-active');
        const idx    = active ? items.indexOf(active) : -1;
        if (e.key === 'ArrowDown')  { e.preventDefault(); active?.classList.remove('ac-active'); (items[idx + 1] ?? items[0])?.classList.add('ac-active'); }
        if (e.key === 'ArrowUp')    { e.preventDefault(); active?.classList.remove('ac-active'); (items[idx - 1] ?? items[items.length - 1])?.classList.add('ac-active'); }
        if (e.key === 'Enter' && active) { e.preventDefault(); active.dispatchEvent(new MouseEvent('mousedown')); }
        if (e.key === 'Escape')     { ul.style.display = 'none'; }
    });
})();
</script>
@endsection

@extends('layouts.app')

@section('title', 'Editar Cotización')

@section('styles')
<style>
    .items-table th, .items-table td { font-size: 0.8rem; padding: 0.4rem 0.5rem; vertical-align: middle; }
    .items-table select.un-sel { width: 120px; font-weight: 700; font-size: 0.78rem; }
    .items-table input.desc-inp { min-width: 200px; }
    .items-table input.price-inp { width: 110px; text-align: right; }

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
<div class="animate-in">
    <div class="mb-4">
        <a href="{{ route('quotations.show', $quotation) }}" class="text-decoration-none text-secondary small fw-medium">
            <i class="bi bi-arrow-left"></i> Volver al detalle
        </a>
        <h2 class="fw-bold mt-2">Editar Cotización <span class="text-primary">#{{ $quotation->folio }}</span></h2>
    </div>

    <form action="{{ route('quotations.update', $quotation) }}" method="POST" id="quotationForm">
        @csrf @method('PUT')
        <div class="row g-4">
            {{-- ── Información General ─── --}}
            <div class="col-lg-3">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3 outfit">Info General</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Cliente <span class="text-danger">*</span></label>
                        <div class="ac-wrap">
                            <input type="text" id="client_text" class="form-control form-control-sm"
                                autocomplete="off"
                                value="{{ old('client_id') ? ($quotation->client->name ?? '') : ($quotation->client->name ?? '') }}">
                            <input type="hidden" name="client_id" id="client_id"
                                value="{{ old('client_id', $quotation->client_id) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Vehículo <span class="text-danger">*</span></label>
                        <div class="ac-wrap">
                            @php
                                $vLabel = $quotation->vehicle
                                    ? $quotation->vehicle->license_plate . ' — ' . $quotation->vehicle->brand . ' ' . $quotation->vehicle->model
                                    : '';
                            @endphp
                            <input type="text" id="vehicle_text" class="form-control form-control-sm"
                                autocomplete="off" value="{{ $vLabel }}">
                            <input type="hidden" name="vehicle_id" id="vehicle_id"
                                value="{{ old('vehicle_id', $quotation->vehicle_id) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control form-control-sm"
                            value="{{ old('date', $quotation->date) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nº Siniestro</label>
                        <input type="text" name="claim_number" class="form-control form-control-sm"
                            value="{{ old('claim_number', $quotation->claim_number) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nº de Ingreso</label>
                        <input type="text" name="intake_number" class="form-control form-control-sm"
                            value="{{ old('intake_number', $quotation->intake_number) }}">
                    </div>

                    <hr class="my-3">
                    <h6 class="fw-bold mb-3 outfit small text-muted text-uppercase" style="font-size:0.65rem;letter-spacing:.08em;">Seguro</h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Compañía</label>
                        <select name="insurance_company_id" class="form-select form-select-sm">
                            <option value="">Ninguna</option>
                            @foreach($insuranceCompanies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('insurance_company_id', $quotation->insurance_company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Liquidador</label>
                        <select name="liquidator_id" class="form-select form-select-sm">
                            <option value="">No asignado</option>
                            @foreach($liquidators as $liq)
                                <option value="{{ $liq->id }}"
                                    {{ old('liquidator_id', $quotation->liquidator_id) == $liq->id ? 'selected' : '' }}>
                                    {{ $liq->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deducible (CLP)</label>
                        <input type="number" name="deductible_amount" class="form-control form-control-sm"
                            value="{{ old('deductible_amount', $quotation->deductible_amount) }}" min="0">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Notas</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ old('notes', $quotation->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── Tabla de Ítems ───────── --}}
            <div class="col-lg-9">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 outfit">Detalle de Trabajos</h5>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" id="addItem">
                            <i class="bi bi-plus-lg"></i> Agregar línea
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless items-table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:130px;">UN</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th class="text-end" style="width:120px;">PRECIO (CLP)</th>
                                    <th class="text-center" style="width:60px;">SALVAR</th>
                                    <th style="width:36px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                            <tfoot>
                                <tr class="fw-bold border-top">
                                    <td colspan="2" class="text-end text-muted small">NETO</td>
                                    <td class="text-end" id="netAmount">$0</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <div style="min-width:260px;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Neto:</span>
                                <span class="fw-semibold" id="netoDisplay">$0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">IVA (19%):</span>
                                <span class="fw-semibold" id="taxAmount">$0</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-top">
                                <span class="h5 fw-bold outfit mb-0">TOTAL:</span>
                                <span class="h5 fw-bold outfit mb-0 text-primary" id="totalAmount">$0</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-light px-4 rounded-pill">Cancelar</a>
                        <button type="submit" class="btn-primary-premium">
                            <i class="bi bi-check-circle"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
const CLP = v => new Intl.NumberFormat('es-CL', {style:'currency',currency:'CLP',maximumFractionDigits:0}).format(v);

// ── Autocomplete ───────────────────────────────────────────────
function initAC({ textId, hiddenId, url, render, label, onSelect }) {
    const txt  = document.getElementById(textId);
    const hid  = document.getElementById(hiddenId);
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
                    li.innerHTML   = render(item);
                    li.dataset.lbl = label(item);
                    li.dataset.id  = item.id;
                    li.addEventListener('mousedown', e => {
                        e.preventDefault();
                        txt.value = li.dataset.lbl;
                        hid.value = li.dataset.id;
                        ul.style.display = 'none';
                        if (onSelect) onSelect(item);
                    });
                    ul.appendChild(li);
                });
                ul.style.display = 'block';
            })
            .catch(() => ul.style.display = 'none');
        }, 220);
    });

    txt.addEventListener('blur', () => setTimeout(() => ul.style.display = 'none', 180));
    txt.addEventListener('keydown', e => {
        const items  = [...ul.querySelectorAll('li')];
        const active = ul.querySelector('li.ac-active');
        const idx    = active ? items.indexOf(active) : -1;
        if (e.key === 'ArrowDown')  { e.preventDefault(); active?.classList.remove('ac-active'); (items[idx + 1] ?? items[0])?.classList.add('ac-active'); }
        if (e.key === 'ArrowUp')    { e.preventDefault(); active?.classList.remove('ac-active'); (items[idx - 1] ?? items[items.length - 1])?.classList.add('ac-active'); }
        if (e.key === 'Enter' && active) { e.preventDefault(); active.dispatchEvent(new MouseEvent('mousedown')); }
        if (e.key === 'Escape')     { ul.style.display = 'none'; }
    });
}

let selectedClientId = '{{ $quotation->client_id }}';

initAC({
    textId:   'client_text',
    hiddenId: 'client_id',
    url:      '{{ route("clients.search") }}',
    render:   c => `<strong>${c.name}</strong> <span style="color:#888;font-size:.8rem;">${c.rut_dni ?? ''}</span>`,
    label:    c => c.name + (c.rut_dni ? ' — ' + c.rut_dni : ''),
    onSelect: c => {
        selectedClientId = c.id;
        document.getElementById('vehicle_text').value = '';
        document.getElementById('vehicle_id').value   = '';
    }
});

initAC({
    textId:   'vehicle_text',
    hiddenId: 'vehicle_id',
    url:      () => '{{ route("vehicles.search") }}' + (selectedClientId ? `?client_id=${selectedClientId}` : ''),
    render:   v => `<strong>${v.license_plate}</strong> <span style="color:#888;font-size:.8rem;">${v.brand} ${v.model}</span>`,
    label:    v => `${v.license_plate} — ${v.brand} ${v.model}`,
});

// ── Tabla de ítems ─────────────────────────────────────────────
const UN_TYPES      = @json($unTypes->map(fn($u) => ['id' => $u->id, 'code' => $u->code, 'name' => $u->name]));
const existingItems = @json($quotation->items);
let rowIdx = 0;

function buildUnOptions(selectedId = null) {
    return UN_TYPES.map(u =>
        `<option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>${u.code} — ${u.name}</option>`
    ).join('');
}

function addRow(data = {}) {
    const i       = rowIdx++;
    const unId    = data.un_type_id  || (UN_TYPES[0]?.id ?? '');
    const desc    = (data.description || '').replace(/"/g, '&quot;');
    const price   = data.price ?? '';
    const salvage = data.is_salvage ? 'checked' : '';

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select name="items[${i}][un_type_id]" class="form-select form-select-sm un-sel fw-bold" required>
                ${buildUnOptions(unId)}
            </select>
        </td>
        <td>
            <input type="text" name="items[${i}][description]" class="form-control form-control-sm desc-inp"
                value="${desc}" placeholder="Descripción del trabajo..." required>
        </td>
        <td>
            <input type="number" name="items[${i}][price]" class="form-control form-control-sm price-inp"
                value="${price}" min="0" step="1" placeholder="0">
        </td>
        <td class="text-center">
            <input type="checkbox" name="items[${i}][is_salvage]" value="1" class="form-check-input" ${salvage}>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 rm-row">
                <i class="bi bi-x-lg"></i>
            </button>
        </td>
    `;

    tr.querySelector('.price-inp').addEventListener('input', recalc);
    tr.querySelector('.rm-row').addEventListener('click', () => { tr.remove(); recalc(); });
    document.getElementById('itemsBody').appendChild(tr);
    recalc();
}

function recalc() {
    let neto = 0;
    document.querySelectorAll('#itemsBody .price-inp').forEach(inp => {
        neto += parseFloat(inp.value) || 0;
    });
    const iva   = Math.round(neto * 0.19);
    const total = neto + iva;
    document.getElementById('netAmount').textContent   = CLP(neto);
    document.getElementById('netoDisplay').textContent = CLP(neto);
    document.getElementById('taxAmount').textContent   = CLP(iva);
    document.getElementById('totalAmount').textContent = CLP(total);
}

document.getElementById('addItem').addEventListener('click', () => addRow());

if (existingItems.length) {
    existingItems.forEach(item => addRow(item));
} else {
    addRow();
}
</script>
@endsection

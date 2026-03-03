@extends('layouts.app')

@section('title', 'Nuevo Presupuesto')

@section('styles')
<style>
    .items-table th, .items-table td { font-size: 0.8rem; padding: 0.4rem 0.35rem; vertical-align: middle; }
    .items-table input[type="number"] { width: 90px; }
    .items-table input[type="text"]   { min-width: 180px; }
    .items-table select.action-sel    { width: 72px; }
    .col-price input { width: 95px; }
</style>
@endsection

@section('content')
<div class="animate-in">
    <div class="mb-4">
        <a href="{{ route('quotations.index') }}" class="text-decoration-none text-secondary small fw-medium">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
        <h2 class="fw-bold mt-2">Crear Nuevo Presupuesto</h2>
    </div>

    <form action="{{ route('quotations.store') }}" method="POST" id="quotationForm">
        @csrf
        <div class="row g-4">
            {{-- ── Información General ─── --}}
            <div class="col-lg-3">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3 outfit">Info General</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Cliente <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id"
                            class="form-select form-select-sm @error('client_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Vehículo <span class="text-danger">*</span></label>
                        <select name="vehicle_id" id="vehicle_id"
                            class="form-select form-select-sm @error('vehicle_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" data-client="{{ $vehicle->client_id }}"
                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->license_plate }} — {{ $vehicle->brand }} {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control form-control-sm"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nº Siniestro</label>
                        <input type="text" name="claim_number" class="form-control form-control-sm"
                            value="{{ old('claim_number') }}" placeholder="Ej: 85593">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nº de Ingreso</label>
                        <input type="text" name="intake_number" class="form-control form-control-sm"
                            value="{{ old('intake_number') }}">
                    </div>

                    <hr class="my-3">
                    <h6 class="fw-bold mb-3 outfit small text-muted text-uppercase" style="font-size:0.65rem;letter-spacing:.08em;">Seguro</h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Compañía</label>
                        <select name="insurance_company_id" class="form-select form-select-sm">
                            <option value="">Ninguna</option>
                            @foreach($insuranceCompanies as $company)
                                <option value="{{ $company->id }}" {{ old('insurance_company_id') == $company->id ? 'selected' : '' }}>
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
                                <option value="{{ $liq->id }}" {{ old('liquidator_id') == $liq->id ? 'selected' : '' }}>
                                    {{ $liq->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deducible (CLP)</label>
                        <input type="number" name="deductible_amount" class="form-control form-control-sm"
                            value="{{ old('deductible_amount', 0) }}" min="0">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Notas</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2"
                            placeholder="Observaciones...">{{ old('notes') }}</textarea>
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
                        <table class="table table-borderless items-table align-middle mb-0" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:68px;">UN</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th class="text-end col-price">REPARAC.</th>
                                    <th class="text-end col-price">PINTURA</th>
                                    <th class="text-end col-price">D/M</th>
                                    <th class="text-end col-price">REPUESTO</th>
                                    <th class="text-end col-price">OTROS</th>
                                    <th class="text-center" style="width:56px;">SALVAR</th>
                                    <th style="width:36px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                            <tfoot>
                                <tr class="fw-bold border-top">
                                    <td colspan="2" class="text-end text-muted small">SUBTOTALES</td>
                                    <td class="text-end" id="sub_repair">$0</td>
                                    <td class="text-end" id="sub_paint">$0</td>
                                    <td class="text-end" id="sub_dm">$0</td>
                                    <td class="text-end" id="sub_parts">$0</td>
                                    <td class="text-end" id="sub_other">$0</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Totals --}}
                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <div style="min-width:260px;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Neto:</span>
                                <span class="fw-semibold" id="netAmount">$0</span>
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
                        <a href="{{ route('quotations.index') }}" class="btn btn-light px-4 rounded-pill">Cancelar</a>
                        <button type="submit" class="btn-primary-premium">
                            <i class="bi bi-check-circle"></i> Guardar Presupuesto
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

let rowIdx = 0;

function addRow(data = {}) {
    const i = rowIdx++;
    const action   = data.action       || 'REP';
    const desc     = data.description  || '';
    const repair   = data.repair_price ?? '';
    const paint    = data.paint_price  ?? '';
    const dm       = data.dm_price     ?? '';
    const parts    = data.parts_price  ?? '';
    const other    = data.other_price  ?? '';
    const salvage  = data.is_salvage   ? 'checked' : '';

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select name="items[${i}][action]" class="form-select form-select-sm action-sel">
                <option value="REP" ${action==='REP'?'selected':''}>REP</option>
                <option value="D/M" ${action==='D/M'?'selected':''}>D/M</option>
                <option value="C"   ${action==='C'  ?'selected':''}>C</option>
                <option value="MAT" ${action==='MAT'?'selected':''}>MAT</option>
            </select>
        </td>
        <td>
            <input type="text" name="items[${i}][description]" class="form-control form-control-sm"
                value="${desc}" placeholder="Descripción del trabajo..." required>
        </td>
        <td class="col-price">
            <input type="number" name="items[${i}][repair_price]" class="form-control form-control-sm price-input text-end"
                value="${repair}" min="0" step="1" placeholder="0">
        </td>
        <td class="col-price">
            <input type="number" name="items[${i}][paint_price]" class="form-control form-control-sm price-input text-end"
                value="${paint}" min="0" step="1" placeholder="0">
        </td>
        <td class="col-price">
            <input type="number" name="items[${i}][dm_price]" class="form-control form-control-sm price-input text-end"
                value="${dm}" min="0" step="1" placeholder="0">
        </td>
        <td class="col-price">
            <input type="number" name="items[${i}][parts_price]" class="form-control form-control-sm price-input text-end"
                value="${parts}" min="0" step="1" placeholder="0">
        </td>
        <td class="col-price">
            <input type="number" name="items[${i}][other_price]" class="form-control form-control-sm price-input text-end"
                value="${other}" min="0" step="1" placeholder="0">
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
    tr.querySelectorAll('.price-input').forEach(el => el.addEventListener('input', recalc));
    tr.querySelector('.rm-row').addEventListener('click', () => { tr.remove(); recalc(); });
    document.getElementById('itemsBody').appendChild(tr);
    recalc();
}

function recalc() {
    let [sRepair, sPaint, sDm, sParts, sOther] = [0,0,0,0,0];
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const inputs = row.querySelectorAll('.price-input');
        sRepair += parseFloat(inputs[0].value)||0;
        sPaint  += parseFloat(inputs[1].value)||0;
        sDm     += parseFloat(inputs[2].value)||0;
        sParts  += parseFloat(inputs[3].value)||0;
        sOther  += parseFloat(inputs[4].value)||0;
    });
    document.getElementById('sub_repair').textContent = CLP(sRepair);
    document.getElementById('sub_paint').textContent  = CLP(sPaint);
    document.getElementById('sub_dm').textContent     = CLP(sDm);
    document.getElementById('sub_parts').textContent  = CLP(sParts);
    document.getElementById('sub_other').textContent  = CLP(sOther);

    const neto  = sRepair + sPaint + sDm + sParts + sOther;
    const iva   = Math.round(neto * 0.19);
    const total = neto + iva;

    document.getElementById('netAmount').textContent   = CLP(neto);
    document.getElementById('taxAmount').textContent   = CLP(iva);
    document.getElementById('totalAmount').textContent = CLP(total);
}

// Filter vehicles by client
document.getElementById('client_id').addEventListener('change', function() {
    const cid = this.value;
    const sel = document.getElementById('vehicle_id');
    Array.from(sel.options).forEach(opt => {
        opt.style.display = (!opt.value || opt.dataset.client === cid) ? '' : 'none';
    });
    sel.value = '';
});

document.getElementById('addItem').addEventListener('click', () => addRow());
addRow(); // initial row
</script>
@endsection

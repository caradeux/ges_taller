@extends('layouts.app')

@section('title', 'Estado del Taller')

@section('styles')
<style>
    .ws-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .ws-top h2 { font-weight: 800; letter-spacing: -0.03em; margin: 0; }

    /* ── Kanban Board ── */
    .kanban {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 1rem;
        min-height: 65vh;
    }
    .kanban-col {
        min-width: 220px;
        max-width: 260px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .kanban-col-header {
        padding: 10px 14px;
        border-radius: 10px 10px 0 0;
        color: white;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kanban-col-header .count {
        background: rgba(255,255,255,0.25);
        padding: 1px 8px;
        border-radius: 99px;
        font-size: 0.72rem;
    }
    .kanban-col-body {
        background: #f1f5f9;
        border-radius: 0 0 10px 10px;
        padding: 8px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-height: 80px;
    }
    .kanban-col-body:empty::after {
        content: 'Sin vehículos';
        color: #94a3b8;
        font-size: 0.78rem;
        text-align: center;
        padding: 1.5rem 0;
        font-style: italic;
    }

    /* ── Vehicle Card ── */
    .k-card {
        background: white;
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: all 0.15s ease;
        position: relative;
    }
    .k-card:hover {
        border-color: var(--primary-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .k-plate {
        font-family: monospace;
        font-weight: 700;
        font-size: 0.88rem;
        color: #1e293b;
        letter-spacing: 0.04em;
    }
    .k-model {
        font-size: 0.72rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .k-client {
        font-size: 0.72rem;
        color: #475569;
        font-weight: 500;
        margin-top: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .k-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 6px;
        padding-top: 6px;
        border-top: 1px solid #f1f5f9;
    }
    .k-amount {
        font-weight: 700;
        font-size: 0.78rem;
        color: #1e293b;
    }
    .k-days {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 1px 6px;
        border-radius: 99px;
    }
    .k-days.ok      { background: #dcfce7; color: #166534; }
    .k-days.warning  { background: #fef3c7; color: #92400e; }
    .k-days.danger   { background: #fee2e2; color: #991b1b; }

    .k-tags {
        display: flex;
        gap: 3px;
        flex-wrap: wrap;
        margin-top: 4px;
    }
    .k-tag {
        font-size: 0.58rem;
        padding: 0px 5px;
        border-radius: 3px;
        font-weight: 600;
    }
    .k-insurance {
        font-size: 0.62rem;
        color: #6366f1;
        font-weight: 600;
    }
    .k-folio {
        font-size: 0.65rem;
        color: var(--primary);
        font-weight: 600;
    }

    /* ── Summary strip ── */
    .summary-strip {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .ss-card {
        background: white;
        border-radius: 10px;
        padding: 12px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        text-align: center;
        flex: 1;
        min-width: 120px;
    }
    .ss-num { font-size: 1.75rem; font-weight: 800; line-height: 1; }
    .ss-label { font-size: 0.68rem; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }

    @media (max-width: 992px) {
        .kanban { flex-direction: column; }
        .kanban-col { max-width: 100%; min-width: 100%; }
        .kanban-col-body { flex-direction: row; flex-wrap: wrap; }
        .k-card { min-width: 200px; flex: 1; }
    }
</style>
@endsection

@section('content')
<div class="animate-in">

    <div class="ws-top">
        <div>
            <h2><i class="bi bi-speedometer2 me-2" style="color:var(--primary);"></i>Estado del Taller</h2>
            <p class="text-muted mb-0">{{ $totalActive }} vehículo{{ $totalActive != 1 ? 's' : '' }} activo{{ $totalActive != 1 ? 's' : '' }} · Monto total: ${{ number_format($totalAmount, 0, ',', '.') }}</p>
        </div>
        @if(auth()->user()->role === 'admin')
        <form method="GET">
            <select name="branch_id" class="form-select form-select-sm" style="min-width:160px;" onchange="this.form.submit()">
                <option value="">Todas las sucursales</option>
                @foreach($branches as $b)
                <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    {{-- Resumen rápido --}}
    <div class="summary-strip">
        @foreach($byStatus as $key => $group)
        <div class="ss-card" style="border-top: 3px solid {{ $group['color'] }};">
            <div class="ss-num" style="color: {{ $group['color'] }};">{{ $group['count'] }}</div>
            <div class="ss-label">{{ $group['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Tablero Kanban --}}
    <div class="kanban">
        @foreach($byStatus as $key => $group)
        <div class="kanban-col">
            <div class="kanban-col-header" style="background: {{ $group['color'] }};">
                {{ $group['label'] }}
                <span class="count">{{ $group['count'] }}</span>
            </div>
            <div class="kanban-col-body">
                @foreach($group['items'] as $wo)
                    @php
                        $days = \Carbon\Carbon::parse($wo->date)->diffInDays(now());
                        $daysClass = $days > 30 ? 'danger' : ($days > 14 ? 'warning' : 'ok');
                    @endphp
                    <a href="{{ route('work-orders.show', $wo) }}" class="k-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="k-plate">{{ $wo->vehicle->license_plate ?? '—' }}</div>
                            <span class="k-folio">{{ $wo->folio ? '#'.$wo->folio : '' }}</span>
                        </div>
                        <div class="k-model">{{ $wo->vehicle->brand ?? '' }} {{ $wo->vehicle->model ?? '' }} {{ $wo->vehicle->year ?? '' }}</div>
                        <div class="k-client"><i class="bi bi-person-fill"></i> {{ $wo->client->name ?? '—' }}</div>
                        @if($wo->insuranceCompany)
                        <div class="k-insurance"><i class="bi bi-shield-check"></i> {{ $wo->insuranceCompany->name }}</div>
                        @endif
                        @if($wo->tags->count())
                        <div class="k-tags">
                            @foreach($wo->tags as $tag)
                            <span class="k-tag" style="background: {{ $tag->color }}20; color: {{ $tag->color }};">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                        <div class="k-footer">
                            <span class="k-amount">${{ number_format($wo->total_amount, 0, ',', '.') }}</span>
                            <span class="k-days {{ $daysClass }}">{{ $days }}d</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    @if($totalActive === 0)
    <div class="card p-5 text-center">
        <i class="bi bi-check-circle" style="font-size:3rem; color:#16a34a;"></i>
        <h5 class="mt-3 fw-bold">Taller vacío</h5>
        <p class="text-muted">No hay vehículos activos en el taller.</p>
    </div>
    @endif

</div>
@endsection

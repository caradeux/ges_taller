@extends('layouts.app')

@section('title', 'Estado del Taller')

@section('styles')
<style>
    .ws-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 1rem;
        padding: 1.5rem 2rem;
        color: white;
        margin-bottom: 1.5rem;
    }
    .ws-header h2 { font-weight: 800; letter-spacing: -0.03em; margin: 0; }
    .ws-header .ws-sub { opacity: 0.7; font-size: 0.85rem; }

    .ws-summary {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
    }
    .ws-summary-item {
        text-align: center;
    }
    .ws-summary-item .ws-num {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }
    .ws-summary-item .ws-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.7;
    }

    .status-section {
        margin-bottom: 1.25rem;
    }
    .status-section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .status-section-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1e293b;
    }
    .status-section-count {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 2px 10px;
        border-radius: 99px;
    }

    .vehicle-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .vehicle-card:hover {
        border-color: var(--primary-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        transform: translateX(4px);
    }

    .vc-plate {
        background: #1e293b;
        color: white;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 4px 12px;
        border-radius: 6px;
        letter-spacing: 0.05em;
        font-family: monospace;
        flex-shrink: 0;
    }
    .vc-info { flex: 1; min-width: 0; }
    .vc-model { font-weight: 600; font-size: 0.88rem; color: #1e293b; }
    .vc-client { font-size: 0.78rem; color: #64748b; }
    .vc-meta {
        text-align: right;
        flex-shrink: 0;
    }
    .vc-folio {
        font-weight: 700;
        font-size: 0.82rem;
        color: var(--primary);
    }
    .vc-date { font-size: 0.72rem; color: #94a3b8; }
    .vc-amount { font-weight: 700; font-size: 0.82rem; color: #1e293b; }
    .vc-days {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 99px;
    }
    .vc-days.warning { background: #fef3c7; color: #92400e; }
    .vc-days.danger  { background: #fee2e2; color: #991b1b; }
    .vc-days.ok      { background: #f0fdf4; color: #166534; }

    .vc-tags { display: flex; gap: 4px; margin-top: 4px; }
    .vc-tag {
        font-size: 0.65rem;
        padding: 1px 6px;
        border-radius: 4px;
        font-weight: 600;
    }

    .empty-status {
        text-align: center;
        padding: 1rem;
        color: #94a3b8;
        font-size: 0.82rem;
        font-style: italic;
    }

    .status-bar {
        display: flex;
        height: 8px;
        border-radius: 99px;
        overflow: hidden;
        margin-top: 1rem;
    }
    .status-bar-segment {
        transition: width 0.5s ease;
    }
</style>
@endsection

@section('content')
<div class="animate-in">

    {{-- Header --}}
    <div class="ws-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-speedometer2 me-2"></i>Estado del Taller</h2>
                <div class="ws-sub">Vista en tiempo real de todos los vehículos en el taller</div>

                <div class="ws-summary">
                    <div class="ws-summary-item">
                        <div class="ws-num">{{ $totalActive }}</div>
                        <div class="ws-label">Vehículos</div>
                    </div>
                    @foreach($byStatus as $key => $group)
                        @if($group['count'] > 0)
                        <div class="ws-summary-item">
                            <div class="ws-num" style="color: {{ $group['color'] }};">{{ $group['count'] }}</div>
                            <div class="ws-label">{{ $group['label'] }}</div>
                        </div>
                        @endif
                    @endforeach
                </div>

                @if($totalActive > 0)
                <div class="status-bar">
                    @foreach($byStatus as $key => $group)
                        @if($group['count'] > 0)
                        <div class="status-bar-segment" style="width: {{ ($group['count'] / $totalActive) * 100 }}%; background: {{ $group['color'] }};"
                            title="{{ $group['label'] }}: {{ $group['count'] }}"></div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            @if(auth()->user()->role === 'admin')
            <form method="GET" class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label text-white" style="font-size:0.72rem; opacity:0.7;">Sucursal</label>
                    <select name="branch_id" class="form-select form-select-sm" style="min-width:150px;" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            @endif
        </div>
    </div>

    {{-- Secciones por estado --}}
    @foreach($byStatus as $key => $group)
        @if($group['count'] > 0)
        <div class="status-section">
            <div class="status-section-header">
                <div class="status-dot" style="background: {{ $group['color'] }};"></div>
                <span class="status-section-title">{{ $group['label'] }}</span>
                <span class="status-section-count">{{ $group['count'] }}</span>
            </div>

            @foreach($group['items'] as $wo)
                @php
                    $days = \Carbon\Carbon::parse($wo->date)->diffInDays(now());
                    $daysClass = $days > 30 ? 'danger' : ($days > 14 ? 'warning' : 'ok');
                @endphp
                <a href="{{ route('work-orders.show', $wo) }}" class="vehicle-card" style="border-left: 4px solid {{ $group['color'] }};">
                    <div class="vc-plate">{{ $wo->vehicle->license_plate ?? '—' }}</div>
                    <div class="vc-info">
                        <div class="vc-model">{{ $wo->vehicle->brand ?? '' }} {{ $wo->vehicle->model ?? '' }} {{ $wo->vehicle->year ?? '' }}</div>
                        <div class="vc-client">
                            <i class="bi bi-person-fill"></i> {{ $wo->client->name ?? '—' }}
                            @if($wo->insuranceCompany)
                                · <i class="bi bi-shield-check"></i> {{ $wo->insuranceCompany->name }}
                            @endif
                        </div>
                        @if($wo->tags->count())
                        <div class="vc-tags">
                            @foreach($wo->tags as $tag)
                            <span class="vc-tag" style="background: {{ $tag->color }}20; color: {{ $tag->color }};">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="vc-meta">
                        <div class="vc-folio">{{ $wo->folio ? '#'.$wo->folio : 'Sin Folio' }}</div>
                        <div class="vc-amount">${{ number_format($wo->total_amount, 0, ',', '.') }}</div>
                        <div class="vc-days {{ $daysClass }}">{{ $days }}d en taller</div>
                        <div class="vc-date">{{ \Carbon\Carbon::parse($wo->date)->format('d/m/Y') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
        @endif
    @endforeach

    @if($totalActive === 0)
    <div class="card p-5 text-center">
        <i class="bi bi-check-circle" style="font-size:3rem; color:#16a34a;"></i>
        <h5 class="mt-3 fw-bold">Taller vacío</h5>
        <p class="text-muted">No hay vehículos activos en el taller en este momento.</p>
    </div>
    @endif

</div>
@endsection

@extends('layouts.app')

@section('title', 'Control de Tiempos — SLA')

@section('styles')
<style>
    .sla-config-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }
    .sla-config-item {
        background: var(--card-bg);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 16px;
        text-align: center;
    }
    .sla-config-item label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        display: block;
        margin-bottom: 8px;
    }
    .sla-config-item input {
        text-align: center;
        font-size: 1.2rem;
        font-weight: 700;
        width: 80px;
        margin: 0 auto;
        display: block;
    }
    .sla-config-item .unit {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .sla-alert-card {
        border-radius: var(--radius);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
        transition: var(--transition);
    }
    .sla-alert-card:hover { transform: translateX(4px); }
    .sla-overdue { background: #fef2f2; border-left: 4px solid #dc2626; }
    .sla-warning { background: #fffbeb; border-left: 4px solid #d97706; }
    .sla-ok { background: #f0fdf4; border-left: 4px solid #16a34a; }

    .sla-days-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .sla-days-overdue { background: #fee2e2; color: #991b1b; }
    .sla-days-warning { background: #fef3c7; color: #92400e; }
    .sla-days-ok { background: #dcfce7; color: #166534; }

    .stage-bar {
        display: flex;
        height: 24px;
        border-radius: 6px;
        overflow: hidden;
        background: var(--border-light);
    }
    .stage-bar-segment {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        font-weight: 700;
        color: white;
        min-width: 2px;
        transition: width 0.3s;
    }
</style>
@endsection

@section('content')
<div class="animate-in">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Control de Tiempos por Etapa</h2>
            <p class="page-subtitle">Configura los tiempos maximos (dias habiles) y monitorea las OTs que los exceden.</p>
        </div>
    </div>

    {{-- ═══ CONFIGURACION SLA ═══ --}}
    <div class="card p-4 mb-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-sliders me-2" style="color:var(--primary);"></i>Tiempos Maximos por Etapa (dias habiles)</h6>
        <form action="{{ route('sla.update') }}" method="POST">
            @csrf
            <div class="sla-config-grid mb-3">
                @foreach($statusLabels as $status => $label)
                <div class="sla-config-item">
                    <label>{{ $label }}</label>
                    <input type="number" name="sla[{{ $status }}]" class="form-control form-control-sm"
                        value="{{ $sla[$status] ?? '' }}" min="1" max="365" required>
                    <div class="unit">dias habiles</div>
                </div>
                @endforeach
            </div>
            <div class="text-end">
                <button type="submit" class="btn-primary-premium">
                    <i class="bi bi-check-lg"></i> Guardar Configuracion
                </button>
            </div>
        </form>
    </div>

    {{-- ═══ RESUMEN ═══ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-4 text-center" style="border-left:4px solid #dc2626;">
                <div style="font-size:2rem;font-weight:800;color:#dc2626;">{{ $overdue->count() }}</div>
                <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">OTs EXCEDIDAS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center" style="border-left:4px solid #d97706;">
                <div style="font-size:2rem;font-weight:800;color:#d97706;">{{ $warning->count() }}</div>
                <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">OTs POR VENCER</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center" style="border-left:4px solid #16a34a;">
                <div style="font-size:2rem;font-weight:800;color:#16a34a;">{{ $ok->count() }}</div>
                <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">OTs EN PLAZO</div>
            </div>
        </div>
    </div>

    {{-- ═══ LISTA DE OTs EXCEDIDAS ═══ --}}
    @if($overdue->count())
    <div class="card mb-4">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h6 class="fw-bold mb-0" style="color:#dc2626;">
                <i class="bi bi-exclamation-octagon-fill me-2"></i>OTs Excedidas ({{ $overdue->count() }})
            </h6>
        </div>
        <div class="p-3">
            @foreach($overdue as $wo)
            <a href="{{ route('work-orders.show', $wo) }}" class="sla-alert-card sla-overdue text-decoration-none">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2">
                        <strong style="color:var(--text-primary);">{{ $wo->folio ? 'OT #'.$wo->folio : 'Sin Folio' }}</strong>
                        <span class="status-badge status-{{ $wo->status }}">{{ $wo->status_label }}</span>
                        <span class="sla-days-badge sla-days-overdue">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            {{ $wo->_business_days }} / {{ $wo->_sla_limit }} dias
                        </span>
                    </div>
                    <div style="font-size:0.8rem;color:var(--text-secondary);margin-top:2px;">
                        {{ $wo->client->name ?? '' }} — {{ strtoupper($wo->vehicle->license_plate ?? '') }}
                    </div>
                </div>
                <i class="bi bi-chevron-right" style="color:var(--text-muted);"></i>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ POR VENCER ═══ --}}
    @if($warning->count())
    <div class="card mb-4">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h6 class="fw-bold mb-0" style="color:#d97706;">
                <i class="bi bi-clock-fill me-2"></i>OTs Por Vencer ({{ $warning->count() }})
            </h6>
        </div>
        <div class="p-3">
            @foreach($warning as $wo)
            <a href="{{ route('work-orders.show', $wo) }}" class="sla-alert-card sla-warning text-decoration-none">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2">
                        <strong style="color:var(--text-primary);">{{ $wo->folio ? 'OT #'.$wo->folio : 'Sin Folio' }}</strong>
                        <span class="status-badge status-{{ $wo->status }}">{{ $wo->status_label }}</span>
                        <span class="sla-days-badge sla-days-warning">
                            <i class="bi bi-clock"></i>
                            {{ $wo->_business_days }} / {{ $wo->_sla_limit }} dias
                        </span>
                    </div>
                    <div style="font-size:0.8rem;color:var(--text-secondary);margin-top:2px;">
                        {{ $wo->client->name ?? '' }} — {{ strtoupper($wo->vehicle->license_plate ?? '') }}
                    </div>
                </div>
                <i class="bi bi-chevron-right" style="color:var(--text-muted);"></i>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ TABLA COMPLETA CON TIEMPOS ═══ --}}
    <div class="card">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-table me-2" style="color:var(--primary);"></i>
                Todas las OTs Activas — Tiempo por Etapa (dias habiles)
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>OT</th>
                        <th>Cliente</th>
                        <th>Vehiculo</th>
                        <th>Estado Actual</th>
                        <th class="text-center">Dias en Etapa</th>
                        <th class="text-center">Limite</th>
                        <th>Distribucion de Tiempo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrders->sortByDesc('_business_days') as $wo)
                    @php
                        $urgClass = match($wo->_sla_urgency) { 'overdue' => 'sla-days-overdue', 'warning' => 'sla-days-warning', default => 'sla-days-ok' };
                        $totalDays = max(array_sum($wo->_stage_times), 1);
                        $stageColors = [
                            'intake' => '#d97706', 'budget_sent' => '#0284c7', 'approved' => '#16a34a',
                            'waiting_parts' => '#ea580c', 'in_repair' => '#7c3aed', 'completed' => '#0d9488',
                            'delivered' => '#6d28d9',
                        ];
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('work-orders.show', $wo) }}" class="fw-bold text-decoration-none"
                                style="color:var(--primary);">{{ $wo->folio ? '#'.$wo->folio : '—' }}</a>
                        </td>
                        <td class="text-sm">{{ $wo->client->name ?? '' }}</td>
                        <td><span class="plate-badge">{{ strtoupper($wo->vehicle->license_plate ?? '') }}</span></td>
                        <td><span class="status-badge status-{{ $wo->status }}">{{ $wo->status_label }}</span></td>
                        <td class="text-center">
                            <span class="sla-days-badge {{ $urgClass }}">{{ $wo->_business_days }}d</span>
                        </td>
                        <td class="text-center text-sm" style="color:var(--text-muted);">{{ $wo->_sla_limit ?? '—' }}d</td>
                        <td>
                            <div class="stage-bar" title="Tiempo total: {{ $totalDays }} dias habiles">
                                @foreach($wo->_stage_times as $stage => $days)
                                @if($days > 0)
                                <div class="stage-bar-segment"
                                    style="width:{{ round($days / $totalDays * 100) }}%;background:{{ $stageColors[$stage] ?? '#94a3b8' }};"
                                    title="{{ $statusLabels[$stage] ?? $stage }}: {{ $days }}d">
                                    @if(round($days / $totalDays * 100) > 12){{ $days }}d @endif
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Leyenda --}}
        <div class="p-3 d-flex flex-wrap gap-3" style="border-top:1px solid var(--border-light);font-size:0.72rem;">
            @php $stageColors = ['intake'=>'#d97706','budget_sent'=>'#0284c7','approved'=>'#16a34a','waiting_parts'=>'#ea580c','in_repair'=>'#7c3aed','completed'=>'#0d9488','delivered'=>'#6d28d9']; @endphp
            @foreach($statusLabels as $status => $label)
            <div class="d-flex align-items-center gap-1">
                <div style="width:12px;height:12px;border-radius:3px;background:{{ $stageColors[$status] ?? '#94a3b8' }};"></div>
                <span style="color:var(--text-muted);">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

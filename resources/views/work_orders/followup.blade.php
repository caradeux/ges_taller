@extends('layouts.app')

@section('title', 'Seguimiento de Órdenes de Trabajo')

@section('styles')
<style>
    .urgency-overdue  { border-left: 4px solid #dc2626 !important; }
    .urgency-critical { border-left: 4px solid #ea580c !important; }
    .urgency-warning  { border-left: 4px solid #d97706 !important; }
    .urgency-ok       { border-left: 4px solid #16a34a !important; }

    .days-badge { font-size: 0.7rem; font-weight: 700; padding: 3px 8px; border-radius: 20px; white-space: nowrap; }
    .days-overdue  { background: #fee2e2; color: #991b1b; }
    .days-critical { background: #ffedd5; color: #9a3412; }
    .days-warning  { background: #fef3c7; color: #92400e; }
    .days-ok       { background: #dcfce7; color: #166534; }

    .client-phone { font-size: 0.8rem; color: #059669; font-weight: 600; }
    .followup-card { transition: box-shadow .15s; }
    .followup-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.08); }

    .section-header { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
                      padding: 6px 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 12px; }
</style>
@endsection

@section('content')
<div class="animate-in">

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('work-orders.index') }}" class="text-decoration-none text-secondary small fw-medium">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
            <h2 class="fw-bold mt-2 mb-1 outfit">Seguimiento de OT</h2>
            <p class="text-secondary small mb-0">
                OTs activas pendientes de resolución. Vigencia configurada: <strong>{{ $validity }} días</strong>.
            </p>
        </div>

        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="days-badge days-overdue">Vencidas</span>
            <span class="days-badge days-critical">≤ 3 días</span>
            <span class="days-badge days-warning">≤ 7 días</span>
            <span class="days-badge days-ok">Vigente</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius:1rem;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($workOrders->isEmpty())
        <div class="card p-5 text-center">
            <i class="bi bi-check2-circle text-success" style="font-size:3rem;"></i>
            <h5 class="mt-3 fw-bold">¡Sin pendientes!</h5>
            <p class="text-secondary mb-0">No hay órdenes de trabajo activas.</p>
        </div>
    @else

        @php
            $byUrgency = $workOrders->getCollection()->groupBy('urgency');
            $sections  = [
                'overdue'  => ['label' => 'Vencidas',              'icon' => 'bi-exclamation-octagon-fill', 'color' => '#dc2626'],
                'critical' => ['label' => 'Vencen en 1–3 días',    'icon' => 'bi-exclamation-triangle-fill','color' => '#ea580c'],
                'warning'  => ['label' => 'Vencen en 4–7 días',    'icon' => 'bi-clock-fill',               'color' => '#d97706'],
                'ok'       => ['label' => 'Vigentes (> 7 días)',    'icon' => 'bi-shield-check-fill',        'color' => '#16a34a'],
            ];
        @endphp

        @foreach($sections as $urgencyKey => $meta)
            @if($byUrgency->has($urgencyKey))
            <div class="mb-4">
                <div class="section-header d-flex align-items-center gap-2" style="color:{{ $meta['color'] }};">
                    <i class="bi {{ $meta['icon'] }}"></i>
                    {{ $meta['label'] }}
                    <span class="badge ms-1" style="background:{{ $meta['color'] }}; color:#fff;">{{ $byUrgency[$urgencyKey]->count() }}</span>
                </div>

                <div class="row g-3">
                    @foreach($byUrgency[$urgencyKey] as $wo)
                    <div class="col-xl-4 col-md-6">
                        <div class="card p-0 followup-card urgency-{{ $wo->urgency }}" style="border-radius:.875rem; overflow:hidden;">
                            <div class="p-3 pb-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="fw-bold" style="font-size:0.95rem;color:{{ $wo->folio ? 'var(--primary)' : 'var(--text-muted)' }};">
                                            {{ $wo->folio ? '#'.$wo->folio : 'Sin Folio' }}
                                        </span>
                                        <span class="ms-2 status-badge status-{{ $wo->status }}" style="font-size:0.65rem;">
                                            {{ $wo->status_label }}
                                        </span>
                                    </div>
                                    <span class="days-badge days-{{ $wo->urgency }}">
                                        @if($wo->days_left < 0)
                                            Venció hace {{ abs($wo->days_left) }}d
                                        @elseif($wo->days_left === 0)
                                            Vence hoy
                                        @else
                                            {{ $wo->days_left }}d restantes
                                        @endif
                                    </span>
                                </div>

                                <div class="mb-1">
                                    <div class="fw-bold" style="font-size:0.9rem;">{{ $wo->client->name }}</div>
                                    @if($wo->client->phone)
                                    @php
                                        $digits = preg_replace('/\D/', '', $wo->client->phone);
                                        if (strlen($digits) <= 9) { $digits = '56' . ltrim($digits, '0'); }
                                        elseif (str_starts_with($digits, '9') && strlen($digits) === 9) { $digits = '569' . substr($digits, 1); }
                                        $vehicle = $wo->vehicle->license_plate . ' ' . $wo->vehicle->brand . ' ' . $wo->vehicle->model;
                                        $msg = urlencode("Hola {$wo->client->name}, le contactamos desde el taller respecto a la OT N° {$wo->folio_display} para su vehículo {$vehicle}.");
                                        $waUrl = "https://wa.me/{$digits}?text={$msg}";
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <a href="tel:{{ $wo->client->phone }}" class="client-phone text-decoration-none">
                                            <i class="bi bi-telephone-fill me-1"></i>{{ $wo->client->phone }}
                                        </a>
                                        <a href="{{ $waUrl }}" target="whatsapp_send" rel="noopener"
                                           style="display:inline-flex;align-items:center;gap:4px;background:#25D366;color:#fff;border-radius:6px;padding:2px 8px;font-size:0.72rem;font-weight:700;text-decoration:none;">
                                            <i class="bi bi-whatsapp"></i> WhatsApp
                                        </a>
                                    </div>
                                    @endif
                                </div>

                                <div class="text-secondary" style="font-size:0.78rem;">
                                    <i class="bi bi-car-front me-1"></i>
                                    <strong>{{ $wo->vehicle->license_plate }}</strong>
                                    {{ $wo->vehicle->brand }} {{ $wo->vehicle->model }}
                                    @if($wo->vehicle->year) ({{ $wo->vehicle->year }}) @endif
                                </div>

                                @if($wo->insuranceCompany)
                                <div class="text-secondary mt-1" style="font-size:0.75rem;">
                                    <i class="bi bi-shield me-1"></i>{{ $wo->insuranceCompany->name }}
                                </div>
                                @endif

                                @if($wo->tags->count())
                                <div class="mt-2 d-flex gap-1 flex-wrap">
                                    @foreach($wo->tags as $tag)
                                    <span class="badge" style="background-color:{{ $tag->color }};font-size:0.65rem;">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="border-top px-3 py-2 d-flex justify-content-between align-items-center" style="background:#f8fafc;">
                                <div>
                                    <span class="text-muted" style="font-size:0.72rem;">Fecha:</span>
                                    <span style="font-size:0.75rem;">{{ \Carbon\Carbon::parse($wo->date)->format('d/m/Y') }}</span>
                                    <span class="text-muted ms-2" style="font-size:0.72rem;">Vence:</span>
                                    <span style="font-size:0.75rem; font-weight:600;">{{ $wo->expiry_date->format('d/m/Y') }}</span>
                                </div>
                                <a href="{{ route('work-orders.show', $wo) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2" style="font-size:0.7rem;" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        <div class="text-center text-muted small mt-4">
            <i class="bi bi-info-circle me-1"></i>
            Total OTs activas: <strong>{{ $workOrders->total() }}</strong>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $workOrders->links() }}
        </div>
    @endif

</div>
@endsection

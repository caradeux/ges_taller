@extends('layouts.app')

@section('title', 'Seguimiento de Cotizaciones')

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
            <a href="{{ route('quotations.index') }}" class="text-decoration-none text-secondary small fw-medium">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
            <h2 class="fw-bold mt-2 mb-1 outfit">Seguimiento de Cotizaciones</h2>
            <p class="text-secondary small mb-0">
                Cotizaciones pendientes de respuesta. Vigencia configurada: <strong>{{ $validity }} días</strong>.
            </p>
        </div>

        <div class="d-flex gap-2 align-items-center flex-wrap">
            {{-- Leyenda --}}
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

    @if($quotations->isEmpty())
        <div class="card p-5 text-center">
            <i class="bi bi-check2-circle text-success" style="font-size:3rem;"></i>
            <h5 class="mt-3 fw-bold">¡Sin pendientes!</h5>
            <p class="text-secondary mb-0">No hay cotizaciones en estado Borrador, Pendiente o Aprobado.</p>
        </div>
    @else

        @php
            $byUrgency = $quotations->getCollection()->groupBy('urgency');
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
                    @foreach($byUrgency[$urgencyKey] as $q)
                    <div class="col-xl-4 col-md-6">
                        <div class="card p-0 followup-card urgency-{{ $q->urgency }}" style="border-radius:.875rem; overflow:hidden;">
                            <div class="p-3 pb-2">
                                {{-- Encabezado de la card --}}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="fw-bold" style="font-size:0.95rem;color:{{ $q->folio ? 'var(--primary)' : 'var(--text-muted)' }};">
                                            {{ $q->folio ? '#'.$q->folio : 'Borrador' }}
                                        </span>
                                        <span class="ms-2 badge rounded-pill
                                            @if($q->status==='draft') bg-warning text-dark
                                            @elseif($q->status==='sent') bg-info text-dark
                                            @else bg-success
                                            @endif" style="font-size:0.65rem;">
                                            {{ $q->status_label }}
                                        </span>
                                    </div>
                                    <span class="days-badge days-{{ $q->urgency }}">
                                        @if($q->days_left < 0)
                                            Venció hace {{ abs($q->days_left) }}d
                                        @elseif($q->days_left === 0)
                                            Vence hoy
                                        @else
                                            {{ $q->days_left }}d restantes
                                        @endif
                                    </span>
                                </div>

                                {{-- Cliente --}}
                                <div class="mb-1">
                                    <div class="fw-bold" style="font-size:0.9rem;">{{ $q->client->name }}</div>
                                    @if($q->client->phone)
                                    @php
                                        // Normaliza el número para wa.me: solo dígitos, agrega 56 si falta
                                        $digits = preg_replace('/\D/', '', $q->client->phone);
                                        if (strlen($digits) <= 9) { $digits = '56' . ltrim($digits, '0'); }
                                        elseif (str_starts_with($digits, '9') && strlen($digits) === 9) { $digits = '569' . substr($digits, 1); }

                                        $vehicle  = $q->vehicle->license_plate . ' ' . $q->vehicle->brand . ' ' . $q->vehicle->model;
                                        $msg = urlencode(
                                            "Hola {$q->client->name}, le contactamos desde el taller respecto a la cotización N° {$q->folio_display} para su vehículo {$vehicle}. ¿Tiene alguna consulta o le gustaría aprobarla?"
                                        );
                                        $waUrl = "https://wa.me/{$digits}?text={$msg}";
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <a href="tel:{{ $q->client->phone }}" class="client-phone text-decoration-none">
                                            <i class="bi bi-telephone-fill me-1"></i>{{ $q->client->phone }}
                                        </a>
                                        <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                                           title="Contactar por WhatsApp"
                                           style="display:inline-flex;align-items:center;gap:4px;
                                                  background:#25D366;color:#fff;border-radius:6px;
                                                  padding:2px 8px;font-size:0.72rem;font-weight:700;
                                                  text-decoration:none;transition:background .15s;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                 fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                            </svg>
                                            WhatsApp
                                        </a>
                                    </div>
                                    @else
                                    <span class="text-muted" style="font-size:0.78rem;">Sin teléfono registrado</span>
                                    @endif
                                </div>

                                {{-- Vehículo --}}
                                <div class="text-secondary" style="font-size:0.78rem;">
                                    <i class="bi bi-car-front me-1"></i>
                                    <strong>{{ $q->vehicle->license_plate }}</strong>
                                    {{ $q->vehicle->brand }} {{ $q->vehicle->model }}
                                    @if($q->vehicle->year) ({{ $q->vehicle->year }}) @endif
                                </div>

                                @if($q->insuranceCompany)
                                <div class="text-secondary mt-1" style="font-size:0.75rem;">
                                    <i class="bi bi-shield me-1"></i>{{ $q->insuranceCompany->name }}
                                </div>
                                @endif
                            </div>

                            {{-- Footer de la card --}}
                            <div class="border-top px-3 py-2 d-flex justify-content-between align-items-center"
                                 style="background:#f8fafc;">
                                <div>
                                    <span class="text-muted" style="font-size:0.72rem;">Fecha:</span>
                                    <span style="font-size:0.75rem;">
                                        {{ \Carbon\Carbon::parse($q->date)->format('d/m/Y') }}
                                    </span>
                                    <span class="text-muted ms-2" style="font-size:0.72rem;">Vence:</span>
                                    <span style="font-size:0.75rem; font-weight:600;">
                                        {{ $q->expiry_date->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="d-flex gap-1">
                                    @if($q->status === 'draft' || $q->status === 'sent')
                                    <form action="{{ route('quotations.status', $q) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $q->status === 'draft' ? 'sent' : 'approved' }}">
                                        <button type="submit" class="btn btn-sm rounded-pill px-2
                                            {{ $q->status === 'draft' ? 'btn-outline-info' : 'btn-outline-success' }}"
                                            style="font-size:0.7rem;"
                                            title="{{ $q->status === 'draft' ? 'Marcar como enviada' : 'Aprobar' }}">
                                            <i class="bi {{ $q->status === 'draft' ? 'bi-send' : 'bi-check-lg' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('quotations.status', $q) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2"
                                            style="font-size:0.7rem;" title="Rechazar"
                                            onclick="return confirm('¿Rechazar cotización ({{ $q->folio_display }})?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('quotations.show', $q) }}"
                                       class="btn btn-sm btn-outline-secondary rounded-pill px-2"
                                       style="font-size:0.7rem;" title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
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
            Total de cotizaciones activas: <strong>{{ $quotations->total() }}</strong>
            &nbsp;·&nbsp;
            Vencidas: <strong class="text-danger">{{ $byUrgency->get('overdue', collect())->count() }}</strong>
            &nbsp;·&nbsp;
            Por vencer (≤7 días): <strong class="text-warning">{{ ($byUrgency->get('critical', collect())->count() + $byUrgency->get('warning', collect())->count()) }}</strong>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $quotations->links() }}
        </div>
    @endif

</div>
@endsection

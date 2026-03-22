@extends('layouts.app')

@section('title', 'Feriados')

@section('content')
<div class="animate-in">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Feriados Legales</h2>
            <p class="page-subtitle">Calendario de feriados para cálculo de días hábiles.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('holidays.seed') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <button type="submit" class="btn-app-secondary" data-confirm="¿Cargar feriados legales de Chile para {{ $year }}?">
                    <i class="bi bi-calendar-plus"></i> Cargar Feriados {{ $year }}
                </button>
            </form>
            <button type="button" class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNewHoliday">
                <i class="bi bi-plus-lg"></i> Agregar Feriado
            </button>
        </div>
    </div>

    {{-- Year selector --}}
    <div class="d-flex gap-2 mb-4">
        @foreach($years as $y)
        <a href="{{ route('holidays.index', ['year' => $y]) }}"
            class="btn btn-sm {{ $y == $year ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
            {{ $y }}
        </a>
        @endforeach
        @if(!$years->contains((int)date('Y')))
        <a href="{{ route('holidays.index', ['year' => date('Y')]) }}"
            class="btn btn-sm btn-outline-primary rounded-pill px-3">
            {{ date('Y') }}
        </a>
        @endif
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Día</th>
                        <th>Nombre</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                    <tr>
                        <td class="fw-600" style="font-weight:600;font-variant-numeric:tabular-nums;">
                            {{ $holiday->date->format('d/m/Y') }}
                        </td>
                        <td style="color:var(--text-secondary);">
                            {{ ucfirst($holiday->date->isoFormat('dddd')) }}
                        </td>
                        <td>
                            <i class="bi bi-calendar-event me-1" style="color:var(--accent);"></i>
                            {{ $holiday->name }}
                        </td>
                        <td class="text-end">
                            <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" class="d-inline"
                                >
                                @csrf @method('DELETE')
                                <button data-confirm="¿Estás seguro de eliminar este registro?" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-calendar-x"></i></div>
                                <p>No hay feriados registrados para {{ $year }}.</p>
                                <form action="{{ route('holidays.seed') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="year" value="{{ $year }}">
                                    <button type="submit" class="btn-primary-premium">
                                        <i class="bi bi-calendar-plus"></i> Cargar Feriados de Chile {{ $year }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Nuevo Feriado --}}
<div class="modal fade" id="modalNewHoliday" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold"><i class="bi bi-calendar-plus me-2" style="color:var(--primary);"></i>Nuevo Feriado</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('holidays.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Ej: Año Nuevo">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium"><i class="bi bi-check-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

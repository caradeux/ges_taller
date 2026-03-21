<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'folio',
        'branch_id',
        'claim_number',
        'intake_number',
        'date',
        'exit_date',
        'repair_start_date',
        'status',
        'vehicle_id',
        'client_id',
        'insurance_company_id',
        'liquidator_id',
        'deductible_amount',
        'total_parts_cost',
        'total_labor_cost',
        'total_surcharge',
        'tax_amount',
        'total_amount',
        'total_workshop',
        'total_authorized',
        'total_real_cost',
        'notes',
        'vehicle_inventory',
        'objects_declaration',
        'conductor_name',
    ];

    protected $casts = [
        'vehicle_inventory' => 'array',
    ];

    public const INVENTORY_ITEMS = [
        'rueda_repuesto'    => 'Rueda de Repuesto',
        'grua'              => 'Grúa',
        'gata'              => 'Gata',
        'kit_seguridad'     => 'Kit de Seguridad',
        'panel_radio'       => 'Panel de Radio',
        'pisos_goma'        => 'Pisos de Goma',
        'antena'            => 'Antena',
        'logos'             => 'Logos',
        'tag'               => 'TAG / Telepeaje',
        'objetos_valor'     => 'Objetos de Valor',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function liquidator()
    {
        return $this->belongsTo(Liquidator::class);
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function events()
    {
        return $this->hasMany(WorkOrderEvent::class)->orderBy('occurred_at', 'desc');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getFolioDisplayAttribute(): string
    {
        return $this->folio ?? 'Sin Folio';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'intake' => 'Ingreso',
            'budget_sent' => 'Presupuesto Enviado',
            'approved' => 'Aprobado',
            'waiting_parts' => 'Esperando Repuestos',
            'in_repair' => 'En Reparación',
            'completed' => 'Completado',
            'delivered' => 'Entregado',
            'invoiced' => 'Facturado',
            default => 'Ingreso',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'intake' => 'warning',
            'budget_sent' => 'info',
            'approved' => 'success',
            'waiting_parts' => 'orange',
            'in_repair' => 'primary',
            'completed' => 'teal',
            'delivered' => 'purple',
            'invoiced' => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Get the date when the current status started (from the latest status_change event).
     */
    public function getCurrentStatusSinceAttribute(): \Carbon\Carbon
    {
        $event = $this->events()
            ->where('event_type', 'status_change')
            ->orderByDesc('occurred_at')
            ->first();

        return $event ? $event->occurred_at : \Carbon\Carbon::parse($this->created_at);
    }

    /**
     * Get business days spent in the current status.
     */
    public function getBusinessDaysInStatusAttribute(): int
    {
        return Holiday::businessDaysBetween(
            $this->current_status_since,
            now()
        );
    }

    /**
     * Get the SLA limit for the current status.
     */
    public function getSlaLimitAttribute(): ?int
    {
        return Company::current()->getSlaForStatus($this->status);
    }

    /**
     * Check if this WO is exceeding its SLA.
     */
    public function getIsOverdueAttribute(): bool
    {
        $limit = $this->sla_limit;
        if ($limit === null || $this->status === 'invoiced') {
            return false;
        }

        return $this->business_days_in_status > $limit;
    }

    /**
     * Get SLA urgency: ok, warning (>75% used), overdue (exceeded).
     */
    public function getSlaUrgencyAttribute(): string
    {
        $limit = $this->sla_limit;
        if ($limit === null || $this->status === 'invoiced') {
            return 'none';
        }

        $days = $this->business_days_in_status;
        if ($days > $limit) {
            return 'overdue';
        }
        if ($days >= $limit * 0.75) {
            return 'warning';
        }

        return 'ok';
    }

    /**
     * Get time spent in each status (for timeline summary).
     */
    public function getStageTimesAttribute(): array
    {
        $events = $this->events()
            ->where('event_type', 'status_change')
            ->orderBy('occurred_at')
            ->get();

        $stages = [];
        $allStatuses = ['intake', 'budget_sent', 'approved', 'waiting_parts', 'in_repair', 'completed', 'delivered', 'invoiced'];

        // Start from creation
        $prevDate = \Carbon\Carbon::parse($this->created_at);
        $prevStatus = 'intake';

        foreach ($events as $event) {
            $newStatus = $event->metadata['new_status'] ?? null;
            if (! $newStatus) continue;

            $days = Holiday::businessDaysBetween($prevDate, $event->occurred_at);
            $stages[$prevStatus] = ($stages[$prevStatus] ?? 0) + $days;

            $prevDate = $event->occurred_at;
            $prevStatus = $newStatus;
        }

        // Current stage (still in progress)
        if ($this->status !== 'invoiced') {
            $days = Holiday::businessDaysBetween($prevDate, now());
            $stages[$prevStatus] = ($stages[$prevStatus] ?? 0) + $days;
        }

        return $stages;
    }
}

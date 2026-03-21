<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderEvent extends Model
{
    protected $fillable = [
        'work_order_id',
        'user_id',
        'event_type',
        'description',
        'occurred_at',
        'metadata',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEventTypeLabelAttribute(): string
    {
        return match ($this->event_type) {
            'intake' => 'Ingreso a Taller',
            'budget_sent' => 'Presupuesto Enviado',
            'insurance_approved' => 'Aprobación del Seguro',
            'parts_ordered' => 'Repuestos Pedidos',
            'parts_arrived' => 'Repuestos Recibidos',
            'repair_start' => 'Inicio de Reparación',
            'repair_end' => 'Fin de Reparación',
            'delivery' => 'Entrega al Cliente',
            'status_change' => 'Cambio de Estado',
            'note' => 'Nota',
            default => ucfirst(str_replace('_', ' ', $this->event_type)),
        };
    }

    public function getEventTypeIconAttribute(): string
    {
        return match ($this->event_type) {
            'intake' => 'bi-box-arrow-in-right',
            'budget_sent' => 'bi-send',
            'insurance_approved' => 'bi-check-circle',
            'parts_ordered' => 'bi-cart',
            'parts_arrived' => 'bi-box-seam',
            'repair_start' => 'bi-wrench',
            'repair_end' => 'bi-check2-circle',
            'delivery' => 'bi-truck',
            'status_change' => 'bi-arrow-repeat',
            'note' => 'bi-sticky',
            default => 'bi-circle',
        };
    }

    public function getEventTypeColorAttribute(): string
    {
        return match ($this->event_type) {
            'intake' => 'warning',
            'budget_sent' => 'info',
            'insurance_approved' => 'success',
            'parts_ordered' => 'primary',
            'parts_arrived' => 'success',
            'repair_start' => 'primary',
            'repair_end' => 'success',
            'delivery' => 'dark',
            'status_change' => 'secondary',
            'note' => 'light',
            default => 'secondary',
        };
    }
}

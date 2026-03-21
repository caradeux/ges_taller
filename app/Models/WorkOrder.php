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
}

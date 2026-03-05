<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'folio',
        'branch_id',
        'claim_number',
        'intake_number',
        'date',
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
        'notes',
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
        return $this->hasMany(QuotationItem::class);
    }

    public function getFolioDisplayAttribute(): string
    {
        return $this->folio ?? 'Borrador';
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Borrador',
            'approved' => 'Aprobado',
            'sent' => 'Pendiente',
            'rejected' => 'Rechazado',
            'finished' => 'Terminado',
            'invoiced' => 'Facturado',
            default => 'Pendiente'
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'warning',
            'approved' => 'success',
            'sent' => 'info',
            'rejected' => 'danger',
            'finished' => 'primary',
            'invoiced' => 'dark',
            default => 'info'
        };
    }
}

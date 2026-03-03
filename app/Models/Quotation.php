<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'folio',
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
        'notes'
    ];

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
}

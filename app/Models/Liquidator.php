<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidator extends Model
{
    protected $fillable = ['name', 'insurance_company_id', 'phone', 'email'];

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }
}

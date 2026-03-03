<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model
{
    protected $fillable = ['name'];

    public function liquidators()
    {
        return $this->hasMany(Liquidator::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(VehicleModel::class);
    }
}

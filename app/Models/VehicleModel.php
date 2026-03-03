<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $fillable = ['vehicle_brand_id', 'name'];

    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }
}

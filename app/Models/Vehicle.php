<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'license_plate',
        'brand',
        'model',
        'year',
        'color',
        'vin_chassis',
        'odometer',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'rut_dni',
        'name',
        'phone',
        'email',
        'address'
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}

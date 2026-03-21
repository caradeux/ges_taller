<?php

namespace App\Models;

use App\Helpers\TextHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use Notifiable;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = TextHelper::toTitleCase($value);
    }

    protected $fillable = [
        'branch_id',
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

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}

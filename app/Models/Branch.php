<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email', 'active'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}

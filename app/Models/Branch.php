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

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
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

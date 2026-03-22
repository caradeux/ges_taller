<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItemType extends Model
{
    protected $fillable = ['name', 'slug', 'active'];

    public function items()
    {
        return $this->hasMany(ServiceItem::class, 'type', 'slug');
    }
}

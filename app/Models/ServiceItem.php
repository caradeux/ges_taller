<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = ['code', 'description', 'type', 'default_price', 'active'];
}

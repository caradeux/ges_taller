<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'action',
        'description',
        'repair_price',
        'paint_price',
        'dm_price',
        'parts_price',
        'other_price',
        'is_salvage',
        'subtotal',
    ];
}

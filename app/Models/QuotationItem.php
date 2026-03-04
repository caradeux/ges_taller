<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'un_type_id',
        'description',
        'price',
        'is_salvage',
    ];

    public function unType()
    {
        return $this->belongsTo(UnType::class);
    }
}

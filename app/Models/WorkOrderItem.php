<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $fillable = [
        'work_order_id',
        'un_type_id',
        'description',
        'price_workshop',
        'price_authorized',
        'price_real',
        'is_approved',
        'is_salvage',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_salvage' => 'boolean',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function unType()
    {
        return $this->belongsTo(UnType::class);
    }

    public function partOrders()
    {
        return $this->hasMany(PartOrder::class);
    }
}

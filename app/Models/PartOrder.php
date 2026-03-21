<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartOrder extends Model
{
    protected $fillable = [
        'work_order_item_id',
        'supplier',
        'part_number',
        'description',
        'cost',
        'ordered_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'ordered_at' => 'date',
        'received_at' => 'date',
    ];

    public function workOrderItem()
    {
        return $this->belongsTo(WorkOrderItem::class);
    }

    public function getStatusAttribute(): string
    {
        if ($this->received_at) {
            return 'received';
        }
        if ($this->ordered_at) {
            return 'ordered';
        }

        return 'pending';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'received' => 'Recibido',
            'ordered' => 'Pedido',
            default => 'Pendiente',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'received' => 'success',
            'ordered' => 'warning',
            default => 'secondary',
        };
    }

    public function getLeadTimeDaysAttribute(): ?int
    {
        if ($this->ordered_at && $this->received_at) {
            return $this->ordered_at->diffInDays($this->received_at);
        }

        return null;
    }
}

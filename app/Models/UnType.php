<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnType extends Model
{
    protected $fillable = ['code', 'name', 'category', 'sort_order', 'active'];

    public static array $categories = [
        'repair' => 'Reparación',
        'paint'  => 'Pintura',
        'dm'     => 'Desmontaje/Montaje',
        'parts'  => 'Repuesto',
        'other'  => 'Otros',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return self::$categories[$this->category] ?? $this->category;
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = ['name', 'rut', 'address', 'phone', 'email'];

    /** Always retrieve the single company record */
    public static function current(): self
    {
        return static::firstOrCreate([], ['name' => 'Mi Taller']);
    }
}

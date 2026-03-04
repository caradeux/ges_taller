<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'label', 'description', 'badge_color'];

    // System roles cannot be deleted
    public function isDeletable(): bool
    {
        return !$this->is_system;
    }

    public function users()
    {
        return User::where('role', $this->name);
    }

    public function usersCount(): int
    {
        return User::where('role', $this->name)->count();
    }
}

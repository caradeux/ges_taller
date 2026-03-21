<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = [
        'name', 'rut', 'address', 'phone', 'email', 'logo_path',
        'quotation_validity_days', 'folio_counter', 'ot_folio_counter', 'stage_sla',
    ];

    protected $casts = [
        'stage_sla' => 'array',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], ['name' => 'Mi Taller']);
    }

    /**
     * Get the SLA (max business days) for a given status.
     */
    public function getSlaForStatus(string $status): ?int
    {
        return $this->stage_sla[$status] ?? null;
    }

    /**
     * Default SLA config.
     */
    public static function defaultSla(): array
    {
        return [
            'intake'        => 2,
            'budget_sent'   => 5,
            'approved'      => 3,
            'waiting_parts' => 15,
            'in_repair'     => 10,
            'completed'     => 2,
            'delivered'     => 3,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    protected $fillable = ['date', 'name', 'year', 'is_recurring'];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Get all holiday dates for a given year as a collection of Carbon dates.
     */
    public static function forYear(int $year): \Illuminate\Support\Collection
    {
        return static::where('year', $year)->pluck('date');
    }

    /**
     * Check if a specific date is a holiday.
     */
    public static function isHoliday(Carbon $date): bool
    {
        return static::where('date', $date->toDateString())->exists();
    }

    /**
     * Count business days (Mon-Fri, excluding holidays) between two dates.
     */
    public static function businessDaysBetween(Carbon $start, Carbon $end): int
    {
        if ($start->gt($end)) {
            return 0;
        }

        $holidays = static::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->pluck('date')
            ->map(fn($d) => $d->toDateString())
            ->toArray();

        $count = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            if ($current->isWeekday() && ! in_array($current->toDateString(), $holidays)) {
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    /**
     * Default Chilean holidays for a given year.
     */
    public static function defaultChileanHolidays(int $year): array
    {
        return [
            ['date' => "{$year}-01-01", 'name' => 'Año Nuevo'],
            ['date' => "{$year}-05-01", 'name' => 'Día del Trabajo'],
            ['date' => "{$year}-05-21", 'name' => 'Día de las Glorias Navales'],
            ['date' => "{$year}-06-20", 'name' => 'Día Nacional de los Pueblos Indígenas'],
            ['date' => "{$year}-06-29", 'name' => 'San Pedro y San Pablo'],
            ['date' => "{$year}-07-16", 'name' => 'Virgen del Carmen'],
            ['date' => "{$year}-08-15", 'name' => 'Asunción de la Virgen'],
            ['date' => "{$year}-09-18", 'name' => 'Independencia Nacional'],
            ['date' => "{$year}-09-19", 'name' => 'Día de las Glorias del Ejército'],
            ['date' => "{$year}-10-12", 'name' => 'Encuentro de Dos Mundos'],
            ['date' => "{$year}-10-31", 'name' => 'Día de las Iglesias Evangélicas'],
            ['date' => "{$year}-11-01", 'name' => 'Día de Todos los Santos'],
            ['date' => "{$year}-12-08", 'name' => 'Inmaculada Concepción'],
            ['date' => "{$year}-12-25", 'name' => 'Navidad'],
        ];
    }
}

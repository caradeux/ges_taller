<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $years = Holiday::selectRaw('DISTINCT year')->orderByDesc('year')->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([(int) date('Y')]);
        }

        $holidays = Holiday::where('year', $year)->orderBy('date')->get();

        return view('holidays.index', compact('holidays', 'year', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'name' => 'required|string|max:255',
        ]);

        $date = \Carbon\Carbon::parse($validated['date']);

        Holiday::create([
            'date' => $validated['date'],
            'name' => $validated['name'],
            'year' => $date->year,
        ]);

        return back()->with('success', 'Feriado agregado.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return back()->with('success', 'Feriado eliminado.');
    }

    public function seedYear(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $defaults = Holiday::defaultChileanHolidays((int) $year);
        $count = 0;

        foreach ($defaults as $h) {
            $exists = Holiday::where('date', $h['date'])->exists();
            if (! $exists) {
                Holiday::create([
                    'date' => $h['date'],
                    'name' => $h['name'],
                    'year' => (int) $year,
                ]);
                $count++;
            }
        }

        return back()->with('success', "Se agregaron {$count} feriados legales para {$year}.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $sixMonthsAgo = now()->subMonths(6);
        $branchId = auth()->user()->activeBranchId();

        $monthlyRevenue = Quotation::where('status', 'invoiced')
            ->where('date', '>=', $sixMonthsAgo)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select(
                DB::raw('SUM(total_amount) as total'),
                DB::raw("MONTH(date) as month"),
                DB::raw("YEAR(date) as year")
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartData = [
            'labels' => $monthlyRevenue->map(fn($m) => \Carbon\Carbon::create(null, (int) $m->month)->format('M')),
            'values' => $monthlyRevenue->pluck('total')
        ];

        $stats = [
            'total_clients'        => Client::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'total_vehicles'       => Vehicle::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'pending_quotations'   => Quotation::whereIn('status', ['draft', 'sent'])
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'approved_quotations'  => Quotation::where('status', 'approved')
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'recent_quotations'    => Quotation::with(['client', 'vehicle', 'branch'])
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                                               ->latest()->take(5)->get(),
            'total_revenue'        => Quotation::where('status', 'invoiced')
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                                               ->sum('total_amount'),
            'total_pending_amount' => Quotation::whereIn('status', ['draft', 'sent', 'approved', 'finished'])
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                                               ->sum('total_amount'),
            'chartData'            => $chartData,
        ];

        return view('dashboard', compact('stats'));
    }
}

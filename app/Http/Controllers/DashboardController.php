<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $sixMonthsAgo = now()->subMonths(6);
        $branchId = auth()->user()->activeBranchId();

        $monthlyRevenue = WorkOrder::where('status', 'invoiced')
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
            'pending_ots'          => WorkOrder::whereIn('status', ['intake', 'budget_sent'])
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'approved_ots'         => WorkOrder::where('status', 'approved')
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'waiting_parts'        => WorkOrder::where('status', 'waiting_parts')
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'in_repair'            => WorkOrder::where('status', 'in_repair')
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'recent_work_orders'   => WorkOrder::with(['client', 'vehicle', 'branch'])
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                                               ->latest()->take(5)->get(),
            'total_revenue'        => WorkOrder::where('status', 'invoiced')
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                                               ->sum('total_amount'),
            'total_pending_amount' => WorkOrder::whereIn('status', ['intake', 'budget_sent', 'approved', 'waiting_parts', 'in_repair', 'completed'])
                                               ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                                               ->sum('total_amount'),
            'chartData'            => $chartData,
        ];

        return view('dashboard', compact('stats'));
    }
}

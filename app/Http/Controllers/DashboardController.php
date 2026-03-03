<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Quotation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_vehicles' => Vehicle::count(),
            'pending_quotations' => Quotation::whereIn('status', ['draft', 'sent'])->count(),
            'approved_quotations' => Quotation::where('status', 'approved')->count(),
            'recent_quotations' => Quotation::with(['client', 'vehicle'])->latest()->take(5)->get()
        ];

        return view('dashboard', compact('stats'));
    }
}

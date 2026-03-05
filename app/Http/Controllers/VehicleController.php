<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Client;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vehicle::with('client')->latest();

        $branchId = auth()->user()->activeBranchId();
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $vehicles = $query->paginate(10)->withQueryString();
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branchId = auth()->user()->activeBranchId() ?? auth()->user()->branch_id;
        $clients  = Client::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                          ->orderBy('name')->get();
        return view('vehicles.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|unique:vehicles,license_plate',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'vin_chassis' => 'nullable|string|unique:vehicles,vin_chassis',
            'odometer' => 'nullable|integer|min:0',
            'client_id' => 'required|exists:clients,id',
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;
        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['client', 'quotations']);
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $vehicle->load('client');
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'license_plate' => 'required|unique:vehicles,license_plate,' . $vehicle->id,
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'vin_chassis' => 'nullable|string|unique:vehicles,vin_chassis,' . $vehicle->id,
            'odometer' => 'nullable|integer|min:0',
            'client_id' => 'required|exists:clients,id',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    /** AJAX search for autocomplete */
    public function search(Request $request)
    {
        $q        = $request->input('q', '');
        $clientId = $request->input('client_id');
        $branchId = auth()->user()->activeBranchId();

        $vehicles = Vehicle::when($branchId, fn($query) => $query->where('branch_id', $branchId))
            ->when($clientId, fn($query) => $query->where('client_id', $clientId))
            ->where(function ($query) use ($q) {
                $query->where('license_plate', 'like', "%{$q}%")
                      ->orWhere('brand', 'like', "%{$q}%")
                      ->orWhere('model', 'like', "%{$q}%");
            })
            ->orderBy('license_plate')
            ->limit(15)
            ->get(['id', 'license_plate', 'brand', 'model', 'client_id']);

        return response()->json($vehicles);
    }

    /** Quick creation via AJAX from quotation form */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'client_id'     => 'required|exists:clients,id',
            'license_plate' => 'required|unique:vehicles,license_plate',
            'brand'         => 'required|string|max:255',
            'model'         => 'required|string|max:255',
            'year'          => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color'         => 'nullable|string|max:50',
            'vin_chassis'   => 'nullable|string|unique:vehicles,vin_chassis',
            'odometer'      => 'nullable|integer|min:0',
        ]);
        $validated['branch_id'] = auth()->user()->branch_id;
        $vehicle = Vehicle::create($validated);
        return response()->json([
            'id'            => $vehicle->id,
            'label'         => $vehicle->license_plate . ' — ' . $vehicle->brand . ' ' . $vehicle->model,
            'client_id'     => $vehicle->client_id,
            'license_plate' => $vehicle->license_plate,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo eliminado exitosamente.');
    }
}

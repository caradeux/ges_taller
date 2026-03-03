<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleBrandController extends Controller
{
    public function index()
    {
        $brands = VehicleBrand::withCount('models')->orderBy('name')->get();
        return view('vehicle_brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:vehicle_brands,name']);
        VehicleBrand::create(['name' => $request->name]);
        return back()->with('success', 'Marca creada.');
    }

    public function update(Request $request, VehicleBrand $vehicleBrand)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:vehicle_brands,name,' . $vehicleBrand->id,
        ]);
        $vehicleBrand->update(['name' => $request->name]);
        return back()->with('success', 'Marca actualizada.');
    }

    public function destroy(VehicleBrand $vehicleBrand)
    {
        $vehicleBrand->delete();
        return back()->with('success', 'Marca eliminada.');
    }

    // ── Models nested actions ──────────────────────────────────────────────

    public function storeModel(Request $request, VehicleBrand $vehicleBrand)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $vehicleBrand->models()->create(['name' => $request->name]);
        return back()->with('success', 'Modelo agregado.');
    }

    public function destroyModel(VehicleBrand $vehicleBrand, VehicleModel $vehicleModel)
    {
        $vehicleModel->delete();
        return back()->with('success', 'Modelo eliminado.');
    }

    /** API: return models for a given brand (used in Vehicle form) */
    public function modelsByBrand(VehicleBrand $vehicleBrand)
    {
        return response()->json($vehicleBrand->models()->orderBy('name')->get(['id', 'name']));
    }

    /** API: return all brands (used in Vehicle form) */
    public function brandsJson()
    {
        return response()->json(VehicleBrand::orderBy('name')->get(['id', 'name']));
    }
}

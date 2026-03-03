<?php

namespace App\Http\Controllers;

use App\Models\Liquidator;
use App\Models\InsuranceCompany;
use Illuminate\Http\Request;

class LiquidatorController extends Controller
{
    public function index()
    {
        $liquidators = Liquidator::with('insuranceCompany')->get();
        $companies = InsuranceCompany::all();
        return view('liquidators.index', compact('liquidators', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Liquidator::create($request->all());

        return redirect()->route('liquidators.index')
            ->with('success', 'Liquidador creado exitosamente.');
    }

    public function update(Request $request, Liquidator $liquidator)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $liquidator->update($request->all());

        return redirect()->route('liquidators.index')
            ->with('success', 'Liquidador actualizado exitosamente.');
    }

    public function destroy(Liquidator $liquidator)
    {
        $liquidator->delete();
        return redirect()->route('liquidators.index')
            ->with('success', 'Liquidador eliminado exitosamente.');
    }
}

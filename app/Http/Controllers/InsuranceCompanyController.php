<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use Illuminate\Http\Request;

class InsuranceCompanyController extends Controller
{
    public function index()
    {
        $companies = InsuranceCompany::withCount('liquidators')->get();
        return view('insurance_companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:insurance_companies,name',
        ]);

        InsuranceCompany::create($request->all());

        return redirect()->route('insurance-companies.index')
            ->with('success', 'Compañía creada exitosamente.');
    }

    public function update(Request $request, InsuranceCompany $insuranceCompany)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:insurance_companies,name,' . $insuranceCompany->id,
        ]);

        $insuranceCompany->update($request->all());

        return redirect()->route('insurance-companies.index')
            ->with('success', 'Compañía actualizada exitosamente.');
    }

    public function destroy(InsuranceCompany $insuranceCompany)
    {
        $insuranceCompany->delete();
        return redirect()->route('insurance-companies.index')
            ->with('success', 'Compañía eliminada exitosamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['users', 'quotations', 'clients', 'vehicles'])
            ->orderBy('name')
            ->get();

        return view('branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:branches,name',
            'address' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:255',
        ]);

        Branch::create(array_merge($validated, ['active' => true]));

        return back()->with('success', 'Sucursal creada exitosamente.');
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:branches,name,' . $branch->id,
            'address' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:255',
            'active'  => 'boolean',
        ]);

        $branch->update($validated);

        return back()->with('success', 'Sucursal actualizada.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una sucursal con usuarios asignados.');
        }

        $branch->delete();

        return back()->with('success', 'Sucursal eliminada.');
    }
}

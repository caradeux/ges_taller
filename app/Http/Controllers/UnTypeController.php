<?php

namespace App\Http\Controllers;

use App\Models\UnType;
use Illuminate\Http\Request;

class UnTypeController extends Controller
{
    public function index()
    {
        $unTypes = UnType::orderBy('sort_order')->orderBy('code')->get();
        $categories = UnType::$categories;
        return view('un_types.index', compact('unTypes', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'       => 'required|string|max:20|unique:un_types,code',
            'name'       => 'required|string|max:255',
            'category'   => 'required|in:repair,paint,dm,parts,other',
            'sort_order' => 'nullable|integer|min:1|max:999',
        ]);

        UnType::create([
            'code'       => strtoupper(trim($request->code)),
            'name'       => $request->name,
            'category'   => $request->category,
            'sort_order' => $request->sort_order ?? 99,
            'active'     => true,
        ]);

        return back()->with('success', 'Tipo de UN creado exitosamente.');
    }

    public function update(Request $request, UnType $unType)
    {
        $request->validate([
            'code'       => 'required|string|max:20|unique:un_types,code,' . $unType->id,
            'name'       => 'required|string|max:255',
            'category'   => 'required|in:repair,paint,dm,parts,other',
            'sort_order' => 'nullable|integer|min:1|max:999',
        ]);

        $unType->update([
            'code'       => strtoupper(trim($request->code)),
            'name'       => $request->name,
            'category'   => $request->category,
            'sort_order' => $request->sort_order ?? 99,
            'active'     => $request->boolean('active', true),
        ]);

        return back()->with('success', 'Tipo de UN actualizado.');
    }

    public function destroy(UnType $unType)
    {
        if ($unType->items()->exists()) {
            return back()->with('error', 'No se puede eliminar: está en uso en cotizaciones.');
        }
        $unType->delete();
        return back()->with('success', 'Tipo de UN eliminado.');
    }

    /** JSON para el formulario de cotización */
    public function json()
    {
        return response()->json(
            UnType::where('active', true)->orderBy('sort_order')->orderBy('code')
                ->get(['id', 'code', 'name', 'category'])
        );
    }
}

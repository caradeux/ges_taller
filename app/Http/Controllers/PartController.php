<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $query = Part::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $parts = $query->orderBy('category')->orderBy('name')->paginate(50)->withQueryString();
        $categories = Part::select('category')->distinct()->orderBy('category')->pluck('category');

        return view('parts.index', compact('parts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:100',
        ]);

        Part::create($validated);

        return redirect()->route('parts.index')->with('success', 'Pieza creada exitosamente.');
    }

    public function update(Request $request, Part $part)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'active'   => 'boolean',
        ]);

        $part->update(array_merge($validated, [
            'active' => $request->boolean('active'),
        ]));

        return redirect()->route('parts.index')->with('success', 'Pieza actualizada.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return back()->with('success', 'Pieza eliminada.');
    }

    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:100',
        ]);

        $part = Part::create(array_merge($validated, ['active' => true]));

        return response()->json($part);
    }

    public function search(Request $request)
    {
        $q = $request->input('q', '');
        $parts = Part::where('active', true)
            ->where('name', 'like', "%$q%")
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'name', 'category']);

        return response()->json($parts);
    }
}

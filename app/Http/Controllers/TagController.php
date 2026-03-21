<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('workOrders')->orderBy('name')->get();

        return view('tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50|unique:tags,name',
            'color' => 'nullable|string|max:20',
        ]);

        Tag::create([
            'name'  => $validated['name'],
            'color' => $validated['color'] ?? '#6c757d',
        ]);

        return redirect()->route('tags.index')->with('success', 'Etiqueta creada.');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50|unique:tags,name,' . $tag->id,
            'color' => 'nullable|string|max:20',
        ]);

        $tag->update($validated);

        return redirect()->route('tags.index')->with('success', 'Etiqueta actualizada.');
    }

    public function destroy(Tag $tag)
    {
        $tag->workOrders()->detach();
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Etiqueta eliminada.');
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $tags = Tag::where('name', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'name', 'color']);

        return response()->json($tags);
    }
}

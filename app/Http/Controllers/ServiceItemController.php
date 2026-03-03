<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use Illuminate\Http\Request;

class ServiceItemController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceItem::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $items = $query->orderBy('description')->paginate(20)->withQueryString();

        return view('service_items.index', compact('items'));
    }

    public function create()
    {
        return view('service_items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'          => 'nullable|string|max:50|unique:service_items,code',
            'description'   => 'required|string|max:255',
            'type'          => 'required|in:repuesto,mano_obra',
            'default_price' => 'required|numeric|min:0',
        ]);

        ServiceItem::create($validated);

        return redirect()->route('service-items.index')->with('success', 'Ítem creado exitosamente.');
    }

    public function edit(ServiceItem $serviceItem)
    {
        return view('service_items.edit', compact('serviceItem'));
    }

    public function update(Request $request, ServiceItem $serviceItem)
    {
        $validated = $request->validate([
            'code'          => 'nullable|string|max:50|unique:service_items,code,' . $serviceItem->id,
            'description'   => 'required|string|max:255',
            'type'          => 'required|in:repuesto,mano_obra',
            'default_price' => 'required|numeric|min:0',
            'active'        => 'boolean',
        ]);

        $serviceItem->update(array_merge($validated, [
            'active' => $request->boolean('active'),
        ]));

        return redirect()->route('service-items.index')->with('success', 'Ítem actualizado exitosamente.');
    }

    public function destroy(ServiceItem $serviceItem)
    {
        $serviceItem->delete();
        return back()->with('success', 'Ítem eliminado.');
    }

    /** API endpoint: search for autocomplete in quotation form */
    public function search(Request $request)
    {
        $q = $request->input('q', '');
        $items = ServiceItem::where('active', true)
            ->where(function ($query) use ($q) {
                $query->where('description', 'like', "%$q%")
                      ->orWhere('code', 'like', "%$q%");
            })
            ->orderBy('description')
            ->limit(10)
            ->get(['id', 'code', 'description', 'type', 'default_price']);

        return response()->json($items);
    }
}

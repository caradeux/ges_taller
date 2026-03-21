<?php

namespace App\Http\Controllers;

use App\Models\PartOrder;
use App\Models\WorkOrder;
use App\Services\WorkOrderTimelineService;
use Illuminate\Http\Request;

class PartOrderController extends Controller
{
    public function __construct(
        private WorkOrderTimelineService $timeline,
    ) {}

    public function store(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'work_order_item_id' => 'required|exists:work_order_items,id',
            'supplier'           => 'nullable|string|max:255',
            'part_number'        => 'nullable|string|max:100',
            'description'        => 'required|string|max:255',
            'cost'               => 'nullable|numeric|min:0',
            'ordered_at'         => 'nullable|date',
        ]);

        $partOrder = PartOrder::create($validated);

        if ($validated['ordered_at'] ?? null) {
            $this->timeline->record($workOrder, 'parts_ordered', "Repuesto pedido: {$validated['description']}");
        }

        return back()->with('success', 'Pedido de repuesto registrado.');
    }

    public function update(Request $request, PartOrder $partOrder)
    {
        $validated = $request->validate([
            'supplier'    => 'nullable|string|max:255',
            'part_number' => 'nullable|string|max:100',
            'description' => 'required|string|max:255',
            'cost'        => 'nullable|numeric|min:0',
            'ordered_at'  => 'nullable|date',
            'received_at' => 'nullable|date',
            'notes'       => 'nullable|string|max:255',
        ]);

        $wasNotReceived = $partOrder->received_at === null;
        $partOrder->update($validated);

        if ($wasNotReceived && ($validated['received_at'] ?? null)) {
            $workOrder = $partOrder->workOrderItem->workOrder;
            $this->timeline->record($workOrder, 'parts_arrived', "Repuesto recibido: {$partOrder->description}");
        }

        return back()->with('success', 'Pedido de repuesto actualizado.');
    }

    public function markReceived(PartOrder $partOrder)
    {
        if ($partOrder->received_at) {
            return back()->with('error', 'Este repuesto ya fue marcado como recibido.');
        }

        $partOrder->update(['received_at' => now()]);

        $workOrder = $partOrder->workOrderItem->workOrder;
        $this->timeline->record($workOrder, 'parts_arrived', "Repuesto recibido: {$partOrder->description}");

        return back()->with('success', 'Repuesto marcado como recibido.');
    }

    public function destroy(PartOrder $partOrder)
    {
        $partOrder->delete();

        return back()->with('success', 'Pedido de repuesto eliminado.');
    }
}

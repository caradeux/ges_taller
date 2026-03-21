<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\InsuranceCompany;
use App\Models\Liquidator;
use App\Models\Tag;
use App\Models\UnType;
use App\Services\WorkOrderTimelineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkOrderController extends Controller
{
    public function __construct(
        private WorkOrderTimelineService $timeline,
    ) {}

    public function index(Request $request)
    {
        $query = WorkOrder::with(['client', 'vehicle', 'branch', 'tags'])->latest();

        $branchId = auth()->user()->activeBranchId();
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%{$search}%")
                    ->orWhereHas('client', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('rut_dni', 'like', "%{$search}%"))
                    ->orWhereHas('vehicle', fn($q) => $q->where('license_plate', 'like', "%{$search}%"))
                    ->orWhereHas('insuranceCompany', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $request->tag));
        }

        $workOrders = $query->paginate(10)->withQueryString();
        $tags = Tag::orderBy('name')->get();

        return view('work_orders.index', compact('workOrders', 'tags'));
    }

    public function create()
    {
        $insuranceCompanies = InsuranceCompany::orderBy('name')->get();
        $liquidators        = Liquidator::orderBy('name')->get();
        $unTypes            = UnType::where('active', true)->orderBy('sort_order')->orderBy('code')->get();
        $tags               = Tag::orderBy('name')->get();

        $oldClient  = old('client_id')  ? Client::select('id', 'name', 'rut_dni')->find(old('client_id')) : null;
        $oldVehicle = old('vehicle_id') ? Vehicle::select('id', 'license_plate', 'brand', 'model')->find(old('vehicle_id')) : null;

        return view('work_orders.create', compact('insuranceCompanies', 'liquidators', 'unTypes', 'tags', 'oldClient', 'oldVehicle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'              => 'required|exists:clients,id',
            'vehicle_id'             => 'required|exists:vehicles,id',
            'date'                   => 'required|date',
            'claim_number'           => 'nullable|string|max:100',
            'intake_number'          => 'nullable|string|max:100',
            'insurance_company_id'   => 'nullable|exists:insurance_companies,id',
            'liquidator_id'          => 'nullable|exists:liquidators,id',
            'deductible_amount'      => 'nullable|numeric|min:0',
            'notes'                  => 'nullable|string',
            'items'                  => 'required|array|min:1',
            'items.*.un_type_id'     => 'required|exists:un_types,id',
            'items.*.description'    => 'required|string',
            'items.*.price_workshop' => 'nullable|numeric|min:0',
            'items.*.price_authorized' => 'nullable|numeric|min:0',
            'items.*.price_real'     => 'nullable|numeric|min:0',
            'items.*.is_approved'    => 'nullable',
            'tags'                   => 'nullable|array',
            'tags.*'                 => 'exists:tags,id',
        ]);

        try {
            DB::beginTransaction();

            $workOrder = WorkOrder::create([
                'branch_id'            => auth()->user()->branch_id,
                'client_id'            => $validated['client_id'],
                'vehicle_id'           => $validated['vehicle_id'],
                'date'                 => $validated['date'],
                'claim_number'         => $validated['claim_number'] ?? null,
                'intake_number'        => $validated['intake_number'] ?? null,
                'insurance_company_id' => $validated['insurance_company_id'] ?? null,
                'liquidator_id'        => $validated['liquidator_id'] ?? null,
                'deductible_amount'    => $validated['deductible_amount'] ?? 0,
                'notes'                => $validated['notes'] ?? null,
                'status'               => 'intake',
                'total_amount'         => 0,
            ]);

            $totals = $this->saveItems($workOrder, $validated['items']);
            $tax = round($totals['authorized'] * 0.19);

            $workOrder->update([
                'total_workshop'   => $totals['workshop'],
                'total_authorized' => $totals['authorized'],
                'total_real_cost'  => $totals['real'],
                'tax_amount'       => $tax,
                'total_amount'     => $totals['authorized'] + $tax,
            ]);

            if (! empty($validated['tags'])) {
                $workOrder->tags()->sync($validated['tags']);
            }

            $this->timeline->record($workOrder, 'intake', 'Orden de trabajo creada');

            DB::commit();

            return redirect()->route('work-orders.show', $workOrder)
                ->with('success', 'Orden de trabajo creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear la OT: ' . $e->getMessage());
        }
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load([
            'client',
            'vehicle',
            'items.unType',
            'items.partOrders',
            'insuranceCompany',
            'liquidator',
            'events.user',
            'tags',
        ]);

        return view('work_orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        if ($workOrder->status === 'invoiced') {
            return redirect()->route('work-orders.show', $workOrder)
                ->with('error', 'Esta OT no puede ser editada en su estado actual.');
        }

        $workOrder->load(['items', 'client', 'vehicle', 'tags']);
        $insuranceCompanies = InsuranceCompany::orderBy('name')->get();
        $liquidators        = Liquidator::orderBy('name')->get();
        $unTypes            = UnType::where('active', true)->orderBy('sort_order')->orderBy('code')->get();
        $tags               = Tag::orderBy('name')->get();

        return view('work_orders.edit', compact('workOrder', 'insuranceCompanies', 'liquidators', 'unTypes', 'tags'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->status === 'invoiced') {
            return redirect()->route('work-orders.show', $workOrder)
                ->with('error', 'Esta OT no puede ser editada en su estado actual.');
        }

        $validated = $request->validate([
            'client_id'              => 'required|exists:clients,id',
            'vehicle_id'             => 'required|exists:vehicles,id',
            'date'                   => 'required|date',
            'claim_number'           => 'nullable|string|max:100',
            'intake_number'          => 'nullable|string|max:100',
            'insurance_company_id'   => 'nullable|exists:insurance_companies,id',
            'liquidator_id'          => 'nullable|exists:liquidators,id',
            'deductible_amount'      => 'nullable|numeric|min:0',
            'notes'                  => 'nullable|string',
            'items'                  => 'required|array|min:1',
            'items.*.un_type_id'     => 'required|exists:un_types,id',
            'items.*.description'    => 'required|string',
            'items.*.price_workshop' => 'nullable|numeric|min:0',
            'items.*.price_authorized' => 'nullable|numeric|min:0',
            'items.*.price_real'     => 'nullable|numeric|min:0',
            'items.*.is_approved'    => 'nullable',
            'tags'                   => 'nullable|array',
            'tags.*'                 => 'exists:tags,id',
        ]);

        try {
            DB::beginTransaction();

            $workOrder->items()->delete();
            $totals = $this->saveItems($workOrder, $validated['items']);
            $tax = round($totals['authorized'] * 0.19);

            $workOrder->update([
                'client_id'            => $validated['client_id'],
                'vehicle_id'           => $validated['vehicle_id'],
                'date'                 => $validated['date'],
                'claim_number'         => $validated['claim_number'] ?? null,
                'intake_number'        => $validated['intake_number'] ?? null,
                'insurance_company_id' => $validated['insurance_company_id'] ?? null,
                'liquidator_id'        => $validated['liquidator_id'] ?? null,
                'deductible_amount'    => $validated['deductible_amount'] ?? 0,
                'notes'                => $validated['notes'] ?? null,
                'total_workshop'       => $totals['workshop'],
                'total_authorized'     => $totals['authorized'],
                'total_real_cost'      => $totals['real'],
                'tax_amount'           => $tax,
                'total_amount'         => $totals['authorized'] + $tax,
            ]);

            $workOrder->tags()->sync($validated['tags'] ?? []);

            DB::commit();

            return redirect()->route('work-orders.show', $workOrder)
                ->with('success', 'Orden de trabajo actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    private function saveItems(WorkOrder $workOrder, array $items): array
    {
        $totalWorkshop = 0;
        $totalAuthorized = 0;
        $totalReal = 0;
        $rows = [];
        $now = now();

        foreach ($items as $item) {
            $pw = (float) ($item['price_workshop'] ?? 0);
            $pa = (float) ($item['price_authorized'] ?? 0);
            $pr = (float) ($item['price_real'] ?? 0);
            $approved = ! empty($item['is_approved']);

            $totalWorkshop += $pw;
            if ($approved) {
                $totalAuthorized += $pa;
            }
            $totalReal += $pr;

            $rows[] = [
                'work_order_id'    => $workOrder->id,
                'un_type_id'       => $item['un_type_id'],
                'description'      => $item['description'],
                'price_workshop'   => $pw,
                'price_authorized' => $pa,
                'price_real'       => $pr,
                'is_approved'      => $approved,
                'is_salvage'       => ! empty($item['is_salvage']),
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        WorkOrderItem::insert($rows);

        return [
            'workshop'   => $totalWorkshop,
            'authorized' => $totalAuthorized,
            'real'       => $totalReal,
        ];
    }

    public function destroy(WorkOrder $workOrder)
    {
        $folio = $workOrder->folio ?? 'sin folio';
        $workOrder->delete();

        return redirect()->route('work-orders.index')
            ->with('success', "OT #{$folio} eliminada.");
    }

    public function downloadPDF(WorkOrder $workOrder)
    {
        if (! $workOrder->folio) {
            return back()->with('error', 'El PDF solo está disponible una vez que la OT tiene folio asignado.');
        }

        $workOrder->load(['client', 'vehicle', 'items.unType', 'insuranceCompany', 'liquidator']);
        $company = Company::current();

        $pdf = Pdf::loadView('work_orders.pdf', compact('workOrder', 'company'));

        return $pdf->download("OT-{$workOrder->folio}.pdf");
    }

    public function downloadInvoicePDF(WorkOrder $workOrder)
    {
        if (! $workOrder->folio) {
            return back()->with('error', 'El PDF de factura solo está disponible con folio asignado.');
        }

        $workOrder->load(['client', 'vehicle', 'insuranceCompany']);
        $company = Company::current();

        $pdf = Pdf::loadView('work_orders.invoice_pdf', compact('workOrder', 'company'));

        return $pdf->download("Factura-OT-{$workOrder->folio}.pdf");
    }

    public function followUp()
    {
        $company  = Company::current();
        $validity = $company->quotation_validity_days ?? 30;
        $branchId = auth()->user()->activeBranchId();

        $workOrders = WorkOrder::with(['client', 'vehicle', 'insuranceCompany', 'tags'])
            ->whereIn('status', ['intake', 'budget_sent', 'approved', 'waiting_parts', 'in_repair'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('date', 'asc')
            ->paginate(30)
            ->through(function ($wo) use ($validity) {
                $expiry = \Carbon\Carbon::parse($wo->date)->addDays($validity);
                $daysLeft = (int) now()->startOfDay()->diffInDays($expiry, false);
                $wo->expiry_date = $expiry;
                $wo->days_left   = $daysLeft;
                $wo->urgency     = $daysLeft < 0 ? 'overdue'
                                 : ($daysLeft <= 3 ? 'critical'
                                 : ($daysLeft <= 7 ? 'warning' : 'ok'));
                return $wo;
            });

        return view('work_orders.followup', compact('workOrders', 'validity'));
    }

    public function updateStatus(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:intake,budget_sent,approved,waiting_parts,in_repair,completed,delivered,invoiced',
        ]);

        $oldStatus = $workOrder->status;
        $newStatus = $validated['status'];

        if ($newStatus === 'budget_sent' && $workOrder->folio === null) {
            try {
                Company::current();
                DB::transaction(function () use ($workOrder, $newStatus) {
                    $company = Company::lockForUpdate()->firstOrFail();
                    $folio = str_pad($company->ot_folio_counter ?? 1, 4, '0', STR_PAD_LEFT);
                    $workOrder->update(['status' => $newStatus, 'folio' => $folio]);
                    $company->increment('ot_folio_counter');
                });
            } catch (\Exception $e) {
                return back()->with('error', 'Error al asignar folio: ' . $e->getMessage());
            }
        } else {
            $workOrder->update(['status' => $newStatus]);
        }

        $this->timeline->recordStatusChange($workOrder, $oldStatus, $newStatus);

        return back()->with('success', 'Estado actualizado a: ' . $workOrder->fresh()->status_label);
    }

    public function toggleItemApproval(WorkOrder $workOrder, WorkOrderItem $item)
    {
        if ($item->work_order_id !== $workOrder->id) {
            abort(403);
        }

        $item->update(['is_approved' => ! $item->is_approved]);

        $approvedTotal = $workOrder->items()->where('is_approved', true)->sum('price_authorized');
        $tax = round($approvedTotal * 0.19);
        $workOrder->update([
            'total_authorized' => $approvedTotal,
            'tax_amount' => $tax,
            'total_amount' => $approvedTotal + $tax,
        ]);

        return response()->json([
            'is_approved' => $item->is_approved,
            'total_authorized' => $approvedTotal,
            'tax_amount' => $tax,
            'total_amount' => $approvedTotal + $tax,
        ]);
    }
}

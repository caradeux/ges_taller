<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\InsuranceCompany;
use App\Models\Liquidator;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with(['client', 'vehicle', 'branch'])->latest();

        // Branch scope
        $branchId = auth()->user()->activeBranchId();
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('rut_dni', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function ($q) use ($search) {
                        $q->where('license_plate', 'like', "%{$search}%");
                    })
                    ->orWhereHas('insuranceCompany', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotations = $query->paginate(10)->withQueryString();

        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $branchId = auth()->user()->activeBranchId() ?? auth()->user()->branch_id;
        $clients  = Client::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                          ->orderBy('name')->get();
        $vehicles = Vehicle::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                           ->orderBy('license_plate')->get();
        $insuranceCompanies = InsuranceCompany::orderBy('name')->get();
        $liquidators        = Liquidator::orderBy('name')->get();

        return view('quotations.create', compact('clients', 'vehicles', 'insuranceCompanies', 'liquidators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'            => 'required|exists:clients,id',
            'vehicle_id'           => 'required|exists:vehicles,id',
            'date'                 => 'required|date',
            'claim_number'         => 'nullable|string|max:100',
            'intake_number'        => 'nullable|string|max:100',
            'insurance_company_id' => 'nullable|exists:insurance_companies,id',
            'liquidator_id'        => 'nullable|exists:liquidators,id',
            'deductible_amount'    => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.action'       => 'required|in:REP,D/M,C,MAT',
            'items.*.description'  => 'required|string',
            'items.*.repair_price' => 'nullable|numeric|min:0',
            'items.*.paint_price'  => 'nullable|numeric|min:0',
            'items.*.dm_price'     => 'nullable|numeric|min:0',
            'items.*.parts_price'  => 'nullable|numeric|min:0',
            'items.*.other_price'  => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $quotation = Quotation::create([
                'folio'                => 'TMP',
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
                'status'               => 'draft',
                'total_amount'         => 0,
            ]);

            $quotation->update(['folio' => str_pad($quotation->id, 4, '0', STR_PAD_LEFT)]);

            $totals = $this->saveItems($quotation, $validated['items']);

            $tax   = round($totals['neto'] * 0.19);
            $total = $totals['neto'] + $tax;

            $quotation->update([
                'total_parts_cost' => $totals['parts'],
                'total_labor_cost' => $totals['labor'],
                'total_surcharge'  => $totals['other'],
                'tax_amount'       => $tax,
                'total_amount'     => $total,
            ]);

            DB::commit();

            return redirect()->route('quotations.show', $quotation)
                ->with('success', "Presupuesto #{$quotation->folio} creado exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear el presupuesto: ' . $e->getMessage());
        }
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['client', 'vehicle', 'items', 'insuranceCompany', 'liquidator']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        if (in_array($quotation->status, ['invoiced', 'rejected'])) {
            return redirect()->route('quotations.show', $quotation)
                ->with('error', 'Este presupuesto no puede ser editado en su estado actual.');
        }

        $quotation->load('items');
        $branchId = $quotation->branch_id;
        $clients  = Client::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                          ->orderBy('name')->get();
        $vehicles = Vehicle::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                           ->orderBy('license_plate')->get();
        $insuranceCompanies = InsuranceCompany::orderBy('name')->get();
        $liquidators = Liquidator::orderBy('name')->get();

        return view('quotations.edit', compact('quotation', 'clients', 'vehicles', 'insuranceCompanies', 'liquidators'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        if (in_array($quotation->status, ['invoiced', 'rejected'])) {
            return redirect()->route('quotations.show', $quotation)
                ->with('error', 'Este presupuesto no puede ser editado en su estado actual.');
        }

        $validated = $request->validate([
            'client_id'            => 'required|exists:clients,id',
            'vehicle_id'           => 'required|exists:vehicles,id',
            'date'                 => 'required|date',
            'claim_number'         => 'nullable|string|max:100',
            'intake_number'        => 'nullable|string|max:100',
            'insurance_company_id' => 'nullable|exists:insurance_companies,id',
            'liquidator_id'        => 'nullable|exists:liquidators,id',
            'deductible_amount'    => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.action'       => 'required|in:REP,D/M,C,MAT',
            'items.*.description'  => 'required|string',
            'items.*.repair_price' => 'nullable|numeric|min:0',
            'items.*.paint_price'  => 'nullable|numeric|min:0',
            'items.*.dm_price'     => 'nullable|numeric|min:0',
            'items.*.parts_price'  => 'nullable|numeric|min:0',
            'items.*.other_price'  => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $quotation->items()->delete();
            $totals = $this->saveItems($quotation, $validated['items']);

            $tax   = round($totals['neto'] * 0.19);
            $total = $totals['neto'] + $tax;

            $quotation->update([
                'client_id'            => $validated['client_id'],
                'vehicle_id'           => $validated['vehicle_id'],
                'date'                 => $validated['date'],
                'claim_number'         => $validated['claim_number'] ?? null,
                'intake_number'        => $validated['intake_number'] ?? null,
                'insurance_company_id' => $validated['insurance_company_id'] ?? null,
                'liquidator_id'        => $validated['liquidator_id'] ?? null,
                'deductible_amount'    => $validated['deductible_amount'] ?? 0,
                'notes'                => $validated['notes'] ?? null,
                'total_parts_cost'     => $totals['parts'],
                'total_labor_cost'     => $totals['labor'],
                'total_surcharge'      => $totals['other'],
                'tax_amount'           => $tax,
                'total_amount'         => $total,
            ]);

            DB::commit();

            return redirect()->route('quotations.show', $quotation)
                ->with('success', 'Presupuesto actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /** Save items and return totals breakdown */
    private function saveItems(Quotation $quotation, array $items): array
    {
        $partsTotal = $laborTotal = $otherTotal = 0;

        foreach ($items as $item) {
            $repair = (float) ($item['repair_price'] ?? 0);
            $paint  = (float) ($item['paint_price']  ?? 0);
            $dm     = (float) ($item['dm_price']     ?? 0);
            $parts  = (float) ($item['parts_price']  ?? 0);
            $other  = (float) ($item['other_price']  ?? 0);
            $sub    = $repair + $paint + $dm + $parts + $other;

            $partsTotal += $parts;
            $laborTotal += $repair + $paint + $dm;
            $otherTotal += $other;

            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'action'       => $item['action'],
                'description'  => $item['description'],
                'repair_price' => $repair,
                'paint_price'  => $paint,
                'dm_price'     => $dm,
                'parts_price'  => $parts,
                'other_price'  => $other,
                'is_salvage'   => !empty($item['is_salvage']),
                'subtotal'     => $sub,
            ]);
        }

        return [
            'parts' => $partsTotal,
            'labor' => $laborTotal,
            'other' => $otherTotal,
            'neto'  => $partsTotal + $laborTotal + $otherTotal,
        ];
    }

    public function destroy(Quotation $quotation)
    {
        $folio = $quotation->folio;
        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('success', "Presupuesto #{$folio} eliminado.");
    }

    public function downloadPDF(Quotation $quotation)
    {
        $quotation->load(['client', 'vehicle', 'items', 'insuranceCompany', 'liquidator']);

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));

        return $pdf->download("Presupuesto-{$quotation->folio}.pdf");
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,approved,finished,rejected,invoiced',
        ]);

        $quotation->update(['status' => $validated['status']]);

        return back()->with('success', 'Estado actualizado a: ' . $quotation->fresh()->status_label);
    }
}

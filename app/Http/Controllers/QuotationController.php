<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Quotation;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\InsuranceCompany;
use App\Models\Liquidator;
use App\Models\QuotationItem;
use App\Models\UnType;
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
        $insuranceCompanies = InsuranceCompany::orderBy('name')->get();
        $liquidators        = Liquidator::orderBy('name')->get();
        $unTypes            = UnType::where('active', true)->orderBy('sort_order')->orderBy('code')->get();

        // Re-populate autocomplete fields when validation fails
        $oldClient  = old('client_id')  ? Client::select('id', 'name', 'rut_dni')->find(old('client_id')) : null;
        $oldVehicle = old('vehicle_id') ? Vehicle::select('id', 'license_plate', 'brand', 'model')->find(old('vehicle_id')) : null;

        return view('quotations.create', compact('insuranceCompanies', 'liquidators', 'unTypes', 'oldClient', 'oldVehicle'));
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
            'items.*.un_type_id'   => 'required|exists:un_types,id',
            'items.*.description'  => 'required|string',
            'items.*.price'        => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $quotation = Quotation::create([
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

            $neto = $this->saveItems($quotation, $validated['items']);
            $tax  = round($neto * 0.19);

            $quotation->update([
                'total_amount' => $neto + $tax,
                'tax_amount'   => $tax,
            ]);

            DB::commit();

            return redirect()->route('quotations.show', $quotation)
                ->with('success', 'Cotización (borrador) creada. El folio se asignará al marcarla como enviada.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear la cotización: ' . $e->getMessage());
        }
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['client', 'vehicle', 'items.unType', 'insuranceCompany', 'liquidator']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        if (in_array($quotation->status, ['invoiced', 'rejected'])) {
            return redirect()->route('quotations.show', $quotation)
                ->with('error', 'Esta cotización no puede ser editada en su estado actual.');
        }

        $quotation->load(['items', 'client', 'vehicle']);
        $insuranceCompanies = InsuranceCompany::orderBy('name')->get();
        $liquidators        = Liquidator::orderBy('name')->get();
        $unTypes            = UnType::where('active', true)->orderBy('sort_order')->orderBy('code')->get();

        return view('quotations.edit', compact('quotation', 'insuranceCompanies', 'liquidators', 'unTypes'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        if (in_array($quotation->status, ['invoiced', 'rejected'])) {
            return redirect()->route('quotations.show', $quotation)
                ->with('error', 'Esta cotización no puede ser editada en su estado actual.');
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
            'items.*.un_type_id'   => 'required|exists:un_types,id',
            'items.*.description'  => 'required|string',
            'items.*.price'        => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $quotation->items()->delete();
            $neto = $this->saveItems($quotation, $validated['items']);
            $tax  = round($neto * 0.19);

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
                'tax_amount'           => $tax,
                'total_amount'         => $neto + $tax,
            ]);

            DB::commit();

            return redirect()->route('quotations.show', $quotation)
                ->with('success', 'Cotización actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /** Save items with a single bulk INSERT, returns neto total */
    private function saveItems(Quotation $quotation, array $items): float
    {
        $neto = 0;
        $rows = [];
        $now  = now();

        foreach ($items as $item) {
            $price  = (float) ($item['price'] ?? 0);
            $neto  += $price;
            $rows[] = [
                'quotation_id' => $quotation->id,
                'un_type_id'   => $item['un_type_id'],
                'description'  => $item['description'],
                'price'        => $price,
                'is_salvage'   => !empty($item['is_salvage']),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        QuotationItem::insert($rows);

        return $neto;
    }

    public function destroy(Quotation $quotation)
    {
        $folio = $quotation->folio ?? 'borrador';
        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('success', "Cotización #{$folio} eliminada.");
    }

    public function downloadPDF(Quotation $quotation)
    {
        if (! $quotation->folio) {
            return back()->with('error', 'El PDF solo está disponible una vez que la cotización tiene folio asignado (estado "Enviada" o superior).');
        }

        $quotation->load(['client', 'vehicle', 'items', 'insuranceCompany', 'liquidator']);
        $company = Company::current();

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation', 'company'));

        return $pdf->download("Presupuesto-{$quotation->folio}.pdf");
    }

    public function followUp()
    {
        $company  = Company::current();
        $validity = $company->quotation_validity_days ?? 30;
        $branchId = auth()->user()->activeBranchId();

        $quotations = Quotation::with(['client', 'vehicle', 'insuranceCompany'])
            ->whereIn('status', ['draft', 'sent', 'approved'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('date', 'asc')
            ->paginate(30)
            ->through(function ($q) use ($validity) {
                $expiry     = \Carbon\Carbon::parse($q->date)->addDays($validity);
                $daysLeft   = (int) now()->startOfDay()->diffInDays($expiry, false);
                $q->expiry_date = $expiry;
                $q->days_left   = $daysLeft;
                $q->urgency     = $daysLeft < 0 ? 'overdue'
                                : ($daysLeft <= 3 ? 'critical'
                                : ($daysLeft <= 7 ? 'warning' : 'ok'));
                return $q;
            });

        return view('quotations.followup', compact('quotations', 'validity'));
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated  = $request->validate([
            'status' => 'required|in:draft,sent,approved,finished,rejected,invoiced',
        ]);
        $newStatus = $validated['status'];

        // Assign folio atomically when first transitioning to 'sent'
        if ($newStatus === 'sent' && $quotation->folio === null) {
            try {
                Company::current(); // ensure record exists before locking
                DB::transaction(function () use ($quotation, $newStatus) {
                    $company = Company::lockForUpdate()->firstOrFail();
                    $folio   = str_pad($company->folio_counter ?? 1, 4, '0', STR_PAD_LEFT);
                    $quotation->update(['status' => $newStatus, 'folio' => $folio]);
                    $company->increment('folio_counter');
                });
            } catch (\Exception $e) {
                return back()->with('error', 'Error al asignar folio: ' . $e->getMessage());
            }
        } else {
            $quotation->update(['status' => $newStatus]);
        }

        return back()->with('success', 'Estado actualizado a: ' . $quotation->fresh()->status_label);
    }
}

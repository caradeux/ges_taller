<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from     = $request->input('from', now()->startOfYear()->toDateString());
        $to       = $request->input('to', now()->toDateString());
        $branchId = $this->resolveBranchId($request);
        $branches = \App\Models\Branch::where('active', true)->orderBy('name')->get();

        $data = $this->buildReportData($from, $to, $branchId);

        return view('reports.index', array_merge($data, compact('from', 'to', 'branchId', 'branches')));
    }

    public function pdf(Request $request)
    {
        $from     = $request->input('from', now()->startOfYear()->toDateString());
        $to       = $request->input('to', now()->toDateString());
        $branchId = $this->resolveBranchId($request);

        $data = $this->buildReportData($from, $to, $branchId);

        $pdf = Pdf::loadView('reports.pdf', array_merge($data, compact('from', 'to')))
            ->setPaper('a4', 'portrait');

        $filename = 'Reporte-Ges_Taller-' . $from . '-al-' . $to . '.pdf';

        return $pdf->download($filename);
    }

    private function resolveBranchId(Request $request): ?int
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $bid = $request->input('branch_id');
            return $bid ? (int) $bid : null;
        }
        return $user->branch_id ? (int) $user->branch_id : null;
    }

    private function buildReportData(string $from, string $to, ?int $branchId = null): array
    {
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate   = Carbon::parse($to)->endOfDay();

        $diffDays = $fromDate->diffInDays($toDate) + 1;
        $prevTo   = $fromDate->copy()->subDay();
        $prevFrom = $prevTo->copy()->subDays($diffDays - 1);

        // ── 1. RESUMEN EJECUTIVO ──────────────────────────────────────────────

        $invoiced = Quotation::whereBetween('date', [$from, $to])->where('status', 'invoiced')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId));

        $totalRevenue    = (clone $invoiced)->sum('total_amount');
        $invoicedCount   = (clone $invoiced)->count();
        $avgTicket       = $invoicedCount > 0 ? $totalRevenue / $invoicedCount : 0;

        $totalQuotations = Quotation::whereBetween('date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
        $approvedOrMore  = Quotation::whereBetween('date', [$from, $to])
            ->whereIn('status', ['approved', 'finished', 'invoiced'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
        $approvalRate    = $totalQuotations > 0 ? round($approvedOrMore / $totalQuotations * 100) : 0;

        $prevRevenue  = Quotation::whereBetween('date', [$prevFrom->toDateString(), $prevTo->toDateString()])
            ->where('status', 'invoiced')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->sum('total_amount');
        $prevCount    = Quotation::whereBetween('date', [$prevFrom->toDateString(), $prevTo->toDateString()])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();

        $revenueChange = $prevRevenue > 0 ? round(($totalRevenue - $prevRevenue) / $prevRevenue * 100, 1) : null;
        $countChange   = $prevCount > 0 ? round(($totalQuotations - $prevCount) / $prevCount * 100, 1) : null;

        $executive = compact(
            'totalRevenue', 'invoicedCount', 'avgTicket', 'totalQuotations',
            'approvalRate', 'prevRevenue', 'revenueChange', 'countChange'
        );

        // ── 2. PIPELINE / EMBUDO ─────────────────────────────────────────────

        $statusLabels = [
            'draft'    => 'Borrador',
            'sent'     => 'Enviado',
            'approved' => 'Aprobado',
            'finished' => 'Terminado',
            'invoiced' => 'Facturado',
            'rejected' => 'Rechazado',
        ];

        $pipelineCounts = Quotation::whereBetween('date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select('status', DB::raw('COUNT(*) as total'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $pipeline = [];
        foreach ($statusLabels as $key => $label) {
            $row = $pipelineCounts->get($key);
            $pipeline[] = [
                'key'    => $key,
                'label'  => $label,
                'count'  => $row?->total ?? 0,
                'amount' => $row?->amount ?? 0,
            ];
        }

        // ── 3. INGRESOS POR ASEGURADORA ──────────────────────────────────────

        $byInsurance = Quotation::whereBetween('date', [$from, $to])
            ->where('status', 'invoiced')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select('insurance_company_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('insurance_company_id')
            ->with('insuranceCompany')
            ->get()
            ->map(fn($row) => [
                'name'  => $row->insuranceCompany?->name ?? 'Particular',
                'count' => $row->count,
                'total' => $row->total,
            ])
            ->sortByDesc('total')
            ->values();

        // ── 4. RANKING DE CLIENTES ───────────────────────────────────────────

        $topClients = Quotation::whereBetween('date', [$from, $to])
            ->whereIn('status', ['approved', 'finished', 'invoiced'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select('client_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('client_id')
            ->with('client')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($row) => [
                'name'  => $row->client?->name ?? 'N/A',
                'rut'   => $row->client?->rut_dni ?? '',
                'count' => $row->count,
                'total' => $row->total,
            ]);

        // ── 5. REPUESTOS VS MANO DE OBRA ─────────────────────────────────────

        $quotationIds = Quotation::whereBetween('date', [$from, $to])
            ->whereIn('status', ['approved', 'finished', 'invoiced'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->pluck('id');

        $itemAgg = QuotationItem::whereIn('quotation_id', $quotationIds)
            ->join('un_types', 'quotation_items.un_type_id', '=', 'un_types.id')
            ->selectRaw('un_types.category, SUM(quotation_items.price) as total')
            ->groupBy('un_types.category')
            ->get()
            ->keyBy('category');

        $repuestoTotal   = $itemAgg->get('parts')?->total  ?? 0;
        $manoObraTotal   = ($itemAgg->get('repair')?->total ?? 0)
                         + ($itemAgg->get('paint')?->total  ?? 0)
                         + ($itemAgg->get('dm')?->total     ?? 0);
        $repuestoCount   = 0;
        $manoObraCount   = 0;
        $itemsGrandTotal = $itemAgg->sum('total');

        $itemTypes = compact('repuestoTotal', 'manoObraTotal', 'repuestoCount', 'manoObraCount', 'itemsGrandTotal');

        // ── Ingresos mensuales ────────────────────────────────────────────────

        $monthlyChart = Quotation::whereBetween('date', [$from, $to])
            ->where('status', 'invoiced')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select(DB::raw("strftime('%Y-%m', date) as month_key"), DB::raw('SUM(total_amount) as total'))
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->map(fn($r) => [
                'label' => Carbon::parse($r->month_key . '-01')->isoFormat('MMM YYYY'),
                'total' => $r->total,
            ]);

        return compact('executive', 'pipeline', 'byInsurance', 'topClients', 'itemTypes', 'monthlyChart');
    }
}

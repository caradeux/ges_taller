<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\PartOrder;
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

        $data    = $this->buildReportData($from, $to, $branchId);
        $company = Company::current();

        $pdf = Pdf::loadView('reports.pdf', array_merge($data, compact('from', 'to', 'company')))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Reporte-Ges_Taller-' . $from . '-al-' . $to . '.pdf');
    }

    public function insuranceReport(Request $request)
    {
        $from     = $request->input('from', now()->startOfYear()->toDateString());
        $to       = $request->input('to', now()->toDateString());
        $branchId = $this->resolveBranchId($request);
        $branches = \App\Models\Branch::where('active', true)->orderBy('name')->get();

        $byInsurance = WorkOrder::whereBetween('date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereNotNull('insurance_company_id')
            ->select(
                'insurance_company_id',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_authorized) as total_authorized'),
                DB::raw('SUM(CASE WHEN status = "invoiced" THEN total_amount ELSE 0 END) as total_invoiced'),
            )
            ->groupBy('insurance_company_id')
            ->with('insuranceCompany')
            ->get()
            ->map(fn($row) => (object) [
                'name'             => $row->insuranceCompany?->name ?? 'Sin Aseguradora',
                'count'            => $row->count,
                'total_authorized' => $row->total_authorized,
                'total_invoiced'   => $row->total_invoiced,
            ])
            ->sortByDesc('total_authorized')
            ->values();

        return view('reports.insurance', compact('byInsurance', 'from', 'to', 'branchId', 'branches'));
    }

    public function profitabilityReport(Request $request)
    {
        $from     = $request->input('from', now()->startOfYear()->toDateString());
        $to       = $request->input('to', now()->toDateString());
        $branchId = $this->resolveBranchId($request);
        $branches = \App\Models\Branch::where('active', true)->orderBy('name')->get();

        $workOrders = WorkOrder::whereBetween('date', [$from, $to])
            ->whereIn('status', ['approved', 'in_repair', 'completed', 'delivered', 'invoiced'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->with(['client', 'vehicle', 'items'])
            ->get()
            ->map(function ($wo) {
                $wo->profit = $wo->total_authorized - $wo->total_real_cost;
                $wo->margin = $wo->total_authorized > 0
                    ? round(($wo->profit / $wo->total_authorized) * 100, 1)
                    : 0;
                return $wo;
            })
            ->sortByDesc('profit')
            ->values();

        $totals = [
            'authorized' => $workOrders->sum('total_authorized'),
            'real_cost'  => $workOrders->sum('total_real_cost'),
            'profit'     => $workOrders->sum('profit'),
        ];
        $totals['margin'] = $totals['authorized'] > 0
            ? round(($totals['profit'] / $totals['authorized']) * 100, 1)
            : 0;

        return view('reports.profitability', compact('workOrders', 'totals', 'from', 'to', 'branchId', 'branches'));
    }

    public function partsReport(Request $request)
    {
        $from     = $request->input('from', now()->startOfYear()->toDateString());
        $to       = $request->input('to', now()->toDateString());
        $branchId = $this->resolveBranchId($request);
        $branches = \App\Models\Branch::where('active', true)->orderBy('name')->get();

        $partStats = PartOrder::whereNotNull('ordered_at')
            ->whereNotNull('received_at')
            ->whereHas('workOrderItem.workOrder', function ($q) use ($from, $to, $branchId) {
                $q->whereBetween('date', [$from, $to]);
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            })
            ->select(
                'supplier',
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(DATEDIFF(received_at, ordered_at)) as avg_days'),
                DB::raw('MAX(DATEDIFF(received_at, ordered_at)) as max_days'),
            )
            ->groupBy('supplier')
            ->orderBy('avg_days', 'desc')
            ->get();

        return view('reports.parts', compact('partStats', 'from', 'to', 'branchId', 'branches'));
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

        // 1. RESUMEN EJECUTIVO
        $invoiced = WorkOrder::whereBetween('date', [$from, $to])->where('status', 'invoiced')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId));

        $totalRevenue    = (clone $invoiced)->sum('total_amount');
        $invoicedCount   = (clone $invoiced)->count();
        $avgTicket       = $invoicedCount > 0 ? $totalRevenue / $invoicedCount : 0;

        $totalWorkOrders = WorkOrder::whereBetween('date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
        $approvedOrMore  = WorkOrder::whereBetween('date', [$from, $to])
            ->whereIn('status', ['approved', 'in_repair', 'completed', 'delivered', 'invoiced'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
        $approvalRate    = $totalWorkOrders > 0 ? round($approvedOrMore / $totalWorkOrders * 100) : 0;

        $prevRevenue  = WorkOrder::whereBetween('date', [$prevFrom->toDateString(), $prevTo->toDateString()])
            ->where('status', 'invoiced')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->sum('total_amount');
        $prevCount    = WorkOrder::whereBetween('date', [$prevFrom->toDateString(), $prevTo->toDateString()])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();

        $revenueChange = $prevRevenue > 0 ? round(($totalRevenue - $prevRevenue) / $prevRevenue * 100, 1) : null;
        $countChange   = $prevCount > 0 ? round(($totalWorkOrders - $prevCount) / $prevCount * 100, 1) : null;

        $executive = compact(
            'totalRevenue', 'invoicedCount', 'avgTicket', 'totalWorkOrders',
            'approvalRate', 'prevRevenue', 'revenueChange', 'countChange'
        );

        // 2. PIPELINE
        $statusLabels = [
            'intake'        => 'Ingreso',
            'budget_sent'   => 'Presupuesto Enviado',
            'approved'      => 'Aprobado',
            'waiting_parts' => 'Esperando Repuestos',
            'in_repair'     => 'En Reparación',
            'completed'     => 'Completado',
            'delivered'     => 'Entregado',
            'invoiced'      => 'Facturado',
        ];

        $pipelineCounts = WorkOrder::whereBetween('date', [$from, $to])
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

        // 3. INGRESOS POR ASEGURADORA
        $byInsurance = WorkOrder::whereBetween('date', [$from, $to])
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

        // 4. RANKING DE CLIENTES
        $topClients = WorkOrder::whereBetween('date', [$from, $to])
            ->whereIn('status', ['approved', 'in_repair', 'completed', 'delivered', 'invoiced'])
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

        // 5. REPUESTOS VS MANO DE OBRA
        $woIds = WorkOrder::whereBetween('date', [$from, $to])
            ->whereIn('status', ['approved', 'in_repair', 'completed', 'delivered', 'invoiced'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->pluck('id');

        $itemAgg = WorkOrderItem::whereIn('work_order_id', $woIds)
            ->join('un_types', 'work_order_items.un_type_id', '=', 'un_types.id')
            ->selectRaw('un_types.category, SUM(work_order_items.price_workshop) as total')
            ->groupBy('un_types.category')
            ->get()
            ->keyBy('category');

        $repuestoTotal   = $itemAgg->get('parts')?->total  ?? 0;
        $manoObraTotal   = ($itemAgg->get('repair')?->total ?? 0)
                         + ($itemAgg->get('paint')?->total  ?? 0)
                         + ($itemAgg->get('dm')?->total     ?? 0);
        $itemsGrandTotal = $itemAgg->sum('total');

        $itemTypes = compact('repuestoTotal', 'manoObraTotal', 'itemsGrandTotal');

        // INGRESOS MENSUALES
        $monthlyChart = WorkOrder::whereBetween('date', [$from, $to])
            ->where('status', 'invoiced')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select(DB::raw("DATE_FORMAT(date, '%Y-%m') as month_key"), DB::raw('SUM(total_amount) as total'))
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

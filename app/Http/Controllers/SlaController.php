<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        $company = Company::current();
        $sla = $company->stage_sla ?? Company::defaultSla();
        $branchId = auth()->user()->activeBranchId();

        // Get active work orders (not invoiced) with SLA info
        $workOrders = WorkOrder::with(['client', 'vehicle', 'events'])
            ->whereNotIn('status', ['invoiced'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('date')
            ->get()
            ->map(function ($wo) {
                $wo->_business_days = $wo->business_days_in_status;
                $wo->_sla_limit = $wo->sla_limit;
                $wo->_sla_urgency = $wo->sla_urgency;
                $wo->_stage_times = $wo->stage_times;
                return $wo;
            });

        $overdue = $workOrders->where('_sla_urgency', 'overdue');
        $warning = $workOrders->where('_sla_urgency', 'warning');
        $ok = $workOrders->where('_sla_urgency', 'ok');

        $statusLabels = [
            'intake'        => 'Ingreso',
            'budget_sent'   => 'Presupuesto Enviado',
            'approved'      => 'Aprobado',
            'waiting_parts' => 'Esperando Repuestos',
            'in_repair'     => 'En Reparación',
            'completed'     => 'Completado',
            'delivered'     => 'Entregado',
        ];

        return view('sla.index', compact('sla', 'workOrders', 'overdue', 'warning', 'ok', 'statusLabels'));
    }

    public function updateSla(Request $request)
    {
        $validated = $request->validate([
            'sla'   => 'required|array',
            'sla.*' => 'required|integer|min:1|max:365',
        ]);

        $company = Company::current();
        $company->update(['stage_sla' => $validated['sla']]);

        return back()->with('success', 'Tiempos máximos por etapa actualizados.');
    }
}

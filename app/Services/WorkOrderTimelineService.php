<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\WorkOrderEvent;
use App\Notifications\WorkOrderIntakeNotification;
use App\Notifications\WorkOrderPartsReceivedNotification;
use App\Notifications\WorkOrderReadyNotification;

class WorkOrderTimelineService
{
    public function record(
        WorkOrder $workOrder,
        string $eventType,
        ?string $description = null,
        ?array $metadata = null,
    ): WorkOrderEvent {
        $event = $workOrder->events()->create([
            'user_id' => auth()->id(),
            'event_type' => $eventType,
            'description' => $description,
            'occurred_at' => now(),
            'metadata' => $metadata,
        ]);

        $this->sendNotificationIfNeeded($workOrder, $eventType, $description);

        return $event;
    }

    private static function statusLabel(string $status): string
    {
        return match ($status) {
            'intake'        => 'Ingreso',
            'budget_sent'   => 'Presupuesto Enviado',
            'approved'      => 'Aprobado',
            'waiting_parts' => 'Esperando Repuestos',
            'in_repair'     => 'En Reparación',
            'completed'     => 'Completado',
            'delivered'     => 'Entregado',
            'invoiced'      => 'Facturado',
            default         => $status,
        };
    }

    public function recordStatusChange(WorkOrder $workOrder, string $oldStatus, string $newStatus): WorkOrderEvent
    {
        $oldLabel = self::statusLabel($oldStatus);
        $newLabel = self::statusLabel($newStatus);

        $event = $this->record(
            $workOrder,
            'status_change',
            "Estado cambiado de {$oldLabel} a {$newLabel}",
            ['old_status' => $oldStatus, 'new_status' => $newStatus],
        );

        // Notify on key status transitions
        if ($newStatus === 'completed' || $newStatus === 'delivered') {
            $this->notifyClient($workOrder, new WorkOrderReadyNotification($workOrder));
        }

        return $event;
    }

    private function sendNotificationIfNeeded(WorkOrder $workOrder, string $eventType, ?string $description): void
    {
        $workOrder->loadMissing(['client', 'vehicle']);

        match ($eventType) {
            'intake' => $this->notifyClient($workOrder, new WorkOrderIntakeNotification($workOrder)),
            'parts_arrived' => $this->notifyClient($workOrder, new WorkOrderPartsReceivedNotification($workOrder, $description ?? '')),
            default => null,
        };
    }

    private function notifyClient(WorkOrder $workOrder, $notification): void
    {
        $client = $workOrder->client;

        if ($client && $client->email) {
            try {
                $client->notify($notification);
            } catch (\Exception $e) {
                // Log silently — don't break the workflow if email fails
                \Log::warning("Failed to send notification: " . $e->getMessage());
            }
        }
    }
}

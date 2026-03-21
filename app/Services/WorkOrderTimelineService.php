<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\WorkOrderEvent;

class WorkOrderTimelineService
{
    public function record(
        WorkOrder $workOrder,
        string $eventType,
        ?string $description = null,
        ?array $metadata = null,
    ): WorkOrderEvent {
        return $workOrder->events()->create([
            'user_id' => auth()->id(),
            'event_type' => $eventType,
            'description' => $description,
            'occurred_at' => now(),
            'metadata' => $metadata,
        ]);
    }

    public function recordStatusChange(WorkOrder $workOrder, string $oldStatus, string $newStatus): WorkOrderEvent
    {
        return $this->record(
            $workOrder,
            'status_change',
            "Estado cambiado de {$oldStatus} a {$newStatus}",
            ['old_status' => $oldStatus, 'new_status' => $newStatus],
        );
    }
}

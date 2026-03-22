<?php

namespace App\Helpers;

use App\Models\WorkOrder;

class WhatsAppHelper
{
    public static function buildStatusMessage(WorkOrder $workOrder, string $newStatus): string
    {
        $workOrder->loadMissing(['client', 'vehicle']);

        $clientName = $workOrder->client->name ?? 'Cliente';
        $plate      = $workOrder->vehicle->license_plate ?? '';
        $brand      = $workOrder->vehicle->brand ?? '';
        $model      = $workOrder->vehicle->model ?? '';
        $folio      = $workOrder->folio ? "OT #{$workOrder->folio}" : 'Su orden de trabajo';
        $company    = \App\Models\Company::current()->name ?? 'GesTaller';

        $messages = [
            'budget_sent'   => "Hola {$clientName}, le informamos que el presupuesto de su vehículo {$plate} ({$brand} {$model}) ha sido enviado. {$folio}. Quedamos atentos a su aprobación.\n\n{$company}",
            'approved'      => "Hola {$clientName}, su presupuesto para el vehículo {$plate} ({$brand} {$model}) ha sido aprobado. Comenzaremos con los trabajos a la brevedad. {$folio}.\n\n{$company}",
            'waiting_parts' => "Hola {$clientName}, le informamos que su vehículo {$plate} está en espera de repuestos. Le avisaremos cuando lleguen para continuar con la reparación. {$folio}.\n\n{$company}",
            'in_repair'     => "Hola {$clientName}, su vehículo {$plate} ({$brand} {$model}) se encuentra en reparación. Le mantendremos informado del avance. {$folio}.\n\n{$company}",
            'completed'     => "Hola {$clientName}, nos complace informarle que los trabajos en su vehículo {$plate} ({$brand} {$model}) han sido completados. Puede coordinar el retiro. {$folio}.\n\n{$company}",
            'delivered'     => "Hola {$clientName}, su vehículo {$plate} ({$brand} {$model}) ha sido entregado exitosamente. Gracias por confiar en {$company}. {$folio}.",
            'invoiced'      => "Hola {$clientName}, su vehículo {$plate} ({$brand} {$model}) ha sido facturado. {$folio}. Gracias por su preferencia.\n\n{$company}",
        ];

        return $messages[$newStatus] ?? "Hola {$clientName}, le informamos que el estado de su vehículo {$plate} ha sido actualizado. {$folio}.\n\n{$company}";
    }

    public static function buildUrl(string $phone, string $message): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '56' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '56') && strlen($phone) <= 9) {
            $phone = '56' . $phone;
        }

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }
}

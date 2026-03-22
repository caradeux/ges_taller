<?php

namespace App\Helpers;

use App\Models\WorkOrder;

class WhatsAppHelper
{
    public static function buildStatusMessage(WorkOrder $workOrder, string $newStatus): string
    {
        $workOrder->loadMissing(['client', 'vehicle', 'insuranceCompany']);

        $nombre   = explode(' ', $workOrder->client->name ?? 'Cliente')[0];
        $plate    = $workOrder->vehicle->license_plate ?? '';
        $auto     = trim(($workOrder->vehicle->brand ?? '') . ' ' . ($workOrder->vehicle->model ?? ''));
        $year     = $workOrder->vehicle->year ?? '';
        $folio    = $workOrder->folio ? "OT N° {$workOrder->folio}" : '';
        $company  = \App\Models\Company::current();
        $taller   = $company->name ?? 'GesTaller';
        $phone    = $company->phone ?? '';
        $total    = '$' . number_format($workOrder->total_amount, 0, ',', '.');

        $header = "🔧 *{$taller}*\n━━━━━━━━━━━━━━━━━━━━\n\n";
        $vehiculo = "🚗 *{$plate}* — {$auto} {$year}";
        $ref = $folio ? "\n📋 {$folio}" : '';
        $firma = "\n\n━━━━━━━━━━━━━━━━━━━━\n📞 {$taller}" . ($phone ? " · {$phone}" : '') . "\n_Mensaje automático — no responder a este número_";

        $messages = [
            'budget_sent' => $header
                . "Estimado/a *{$nombre}*, le informamos que hemos preparado el presupuesto para su vehículo:\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "💰 *Monto presupuestado: {$total}*\n\n"
                . "Quedamos atentos a su aprobación para comenzar con los trabajos de reparación. Si tiene alguna consulta sobre el detalle del presupuesto, no dude en contactarnos."
                . $firma,

            'approved' => $header
                . "Estimado/a *{$nombre}*, le confirmamos que el presupuesto de su vehículo ha sido *aprobado* ✅\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Nuestro equipo comenzará con los trabajos a la brevedad. Le mantendremos informado/a sobre el avance de la reparación."
                . $firma,

            'waiting_parts' => $header
                . "Estimado/a *{$nombre}*, le informamos que su vehículo se encuentra en *espera de repuestos* 📦\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Ya realizamos el pedido de las piezas necesarias. Una vez que lleguen, continuaremos de inmediato con la reparación. Le notificaremos cuando retomemos los trabajos."
                . $firma,

            'in_repair' => $header
                . "Estimado/a *{$nombre}*, le informamos que su vehículo se encuentra *en reparación* 🔧\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Nuestro equipo está trabajando en su vehículo. Le notificaremos una vez que los trabajos estén finalizados."
                . $firma,

            'completed' => $header
                . "Estimado/a *{$nombre}*, nos complace informarle que los trabajos en su vehículo han sido *completados exitosamente* ✅🎉\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Su vehículo se encuentra listo para ser retirado. Por favor contáctenos para *coordinar la fecha y hora de entrega*.\n\n"
                . "⏰ Horario de atención: Lunes a Viernes 9:00 - 18:00"
                . $firma,

            'delivered' => $header
                . "Estimado/a *{$nombre}*, confirmamos la *entrega exitosa* de su vehículo 🚗✅\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Agradecemos su confianza en *{$taller}*. Si nota cualquier detalle o tiene alguna consulta posterior, no dude en contactarnos.\n\n"
                . "⭐ Su opinión es importante para nosotros."
                . $firma,

            'invoiced' => $header
                . "Estimado/a *{$nombre}*, le informamos que su orden de trabajo ha sido *facturada* 🧾\n\n"
                . "{$vehiculo}{$ref}\n"
                . "💰 *Total: {$total}*\n\n"
                . "Gracias por su preferencia. Fue un gusto atenderle en *{$taller}*."
                . $firma,
        ];

        return $messages[$newStatus] ?? $header . "Estimado/a *{$nombre}*, le informamos que el estado de su vehículo ha sido actualizado.\n\n{$vehiculo}{$ref}" . $firma;
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

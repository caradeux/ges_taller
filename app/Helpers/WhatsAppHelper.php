<?php

namespace App\Helpers;

use App\Models\WorkOrder;

class WhatsAppHelper
{
    private static function saludo(): string
    {
        $hora = (int) now()->format('H');
        if ($hora >= 6 && $hora < 12) return 'Buenos días';
        if ($hora >= 12 && $hora < 20) return 'Buenas tardes';
        return 'Buenas noches';
    }

    public static function buildStatusMessage(WorkOrder $workOrder, string $newStatus): string
    {
        $workOrder->loadMissing(['client', 'vehicle', 'insuranceCompany']);

        $nombre   = explode(' ', $workOrder->client->name ?? 'Cliente')[0];
        $plate    = $workOrder->vehicle->license_plate ?? '';
        $auto     = trim(($workOrder->vehicle->brand ?? '') . ' ' . ($workOrder->vehicle->model ?? ''));
        $year     = $workOrder->vehicle->year ?? '';
        $folio    = $workOrder->folio ? "OT N° {$workOrder->folio}" : '';
        $company  = \App\Models\Company::current();
        $taller   = $company->name ?? 'Nuestro taller';

        // Use branch phone if available, fallback to company phone
        $branch = $workOrder->branch_id ? \App\Models\Branch::find($workOrder->branch_id) : null;
        $phone  = $branch?->phone ?? $company->phone ?? '';

        $total    = '$' . number_format($workOrder->total_amount, 0, ',', '.');
        $saludo   = self::saludo();

        $vehiculo = "🚗 *{$plate}* — {$auto} {$year}";
        $ref = $folio ? "\n📋 {$folio}" : '';
        $firma = "\n\nQuedamos a su disposición para cualquier consulta."
            . "\n\nSaludos cordiales,\n*{$taller}*"
            . ($phone ? "\n📞 {$phone}" : '');

        $messages = [
            'budget_sent' =>
                "{$saludo} *{$nombre}* 👋\n\n"
                . "Le informamos que hemos preparado el presupuesto de reparación para su vehículo:\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "💰 *Monto presupuestado: {$total}*\n\n"
                . "Quedamos atentos a su aprobación para dar inicio a los trabajos. Si tiene alguna consulta sobre el detalle, no dude en escribirnos."
                . $firma,

            'approved' =>
                "{$saludo} *{$nombre}* 👋\n\n"
                . "Le confirmamos que el presupuesto de su vehículo ha sido *aprobado* ✅\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Nuestro equipo comenzará con los trabajos de reparación a la brevedad. Le mantendremos informado/a sobre cada avance."
                . $firma,

            'waiting_parts' =>
                "{$saludo} *{$nombre}* 👋\n\n"
                . "Le informamos que su vehículo se encuentra actualmente en *espera de repuestos* 📦\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Ya realizamos el pedido de las piezas necesarias para su reparación. Una vez que lleguen, retomaremos los trabajos de inmediato y le notificaremos."
                . $firma,

            'in_repair' =>
                "{$saludo} *{$nombre}* 👋\n\n"
                . "Le informamos que su vehículo ya se encuentra *en proceso de reparación* 🔧\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Nuestro equipo técnico está trabajando para dejarlo en óptimas condiciones. Le avisaremos una vez que los trabajos estén finalizados."
                . $firma,

            'completed' =>
                "{$saludo} *{$nombre}* 👋\n\n"
                . "Nos complace informarle que los trabajos en su vehículo han sido *completados exitosamente* ✅🎉\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Su vehículo se encuentra listo para ser retirado. Por favor contáctenos para *coordinar la fecha y hora de entrega* que más le acomode.\n\n"
                . "⏰ Nuestro horario de atención es de Lunes a Viernes, de 9:00 a 18:00 hrs."
                . $firma,

            'delivered' =>
                "{$saludo} *{$nombre}* 👋\n\n"
                . "Confirmamos la *entrega exitosa* de su vehículo 🚗✅\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Agradecemos enormemente su confianza. Si en los próximos días nota cualquier detalle relacionado con la reparación, no dude en contactarnos.\n\n"
                . "⭐ Su opinión es muy importante para nosotros. ¡Fue un gusto atenderle!"
                . $firma,

            'invoiced' =>
                "{$saludo} *{$nombre}* 👋\n\n"
                . "Le informamos que su orden de trabajo ha sido *facturada* 🧾\n\n"
                . "{$vehiculo}{$ref}\n"
                . "💰 *Total facturado: {$total}*\n\n"
                . "Muchas gracias por su preferencia. Fue un gusto atenderle y esperamos poder servirle nuevamente en el futuro."
                . $firma,
        ];

        return $messages[$newStatus] ?? "{$saludo} *{$nombre}* 👋\n\nLe informamos que el estado de su vehículo ha sido actualizado.\n\n{$vehiculo}{$ref}" . $firma;
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

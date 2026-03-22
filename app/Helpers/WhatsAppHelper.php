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

        $vehiculo = "Vehiculo: *{$plate}* - {$auto} {$year}";
        $ref = $folio ? "\nReferencia: {$folio}" : '';
        $firma = "\n\nQuedamos a su disposicion para cualquier consulta."
            . "\n\nSaludos cordiales,\n*{$taller}*"
            . ($phone ? "\nTel: {$phone}" : '');

        $messages = [
            'budget_sent' =>
                "{$saludo} *{$nombre}*,\n\n"
                . "Le saluda *{$taller}*. Le informamos que hemos preparado el presupuesto de reparacion para su vehiculo:\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Monto presupuestado: *{$total}*\n\n"
                . "Quedamos atentos a su aprobacion para dar inicio a los trabajos. Si tiene alguna consulta sobre el detalle, no dude en escribirnos."
                . $firma,

            'approved' =>
                "{$saludo} *{$nombre}*,\n\n"
                . "Le saluda *{$taller}*. Le confirmamos que el presupuesto de su vehiculo ha sido *aprobado*.\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Nuestro equipo comenzara con los trabajos de reparacion a la brevedad. Le mantendremos informado/a sobre cada avance."
                . $firma,

            'waiting_parts' =>
                "{$saludo} *{$nombre}*,\n\n"
                . "Le saluda *{$taller}*. Le informamos que su vehiculo se encuentra actualmente en *espera de repuestos*.\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Ya realizamos el pedido de las piezas necesarias para su reparacion. Una vez que lleguen, retomaremos los trabajos de inmediato y le notificaremos."
                . $firma,

            'in_repair' =>
                "{$saludo} *{$nombre}*,\n\n"
                . "Le saluda *{$taller}*. Le informamos que su vehiculo ya se encuentra *en proceso de reparacion*.\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Nuestro equipo tecnico esta trabajando para dejarlo en optimas condiciones. Le avisaremos una vez que los trabajos esten finalizados."
                . $firma,

            'completed' =>
                "{$saludo} *{$nombre}*,\n\n"
                . "Le saluda *{$taller}*. Nos complace informarle que los trabajos en su vehiculo han sido *completados exitosamente*.\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Su vehiculo se encuentra listo para ser retirado. Por favor contactenos para *coordinar la fecha y hora de entrega* que mas le acomode.\n\n"
                . "Horario de atencion: Lunes a Viernes, 9:00 a 18:00 hrs."
                . $firma,

            'delivered' =>
                "{$saludo} *{$nombre}*,\n\n"
                . "Le saluda *{$taller}*. Confirmamos la *entrega exitosa* de su vehiculo.\n\n"
                . "{$vehiculo}{$ref}\n\n"
                . "Agradecemos enormemente su confianza. Si en los proximos dias nota cualquier detalle relacionado con la reparacion, no dude en contactarnos.\n\n"
                . "Su opinion es muy importante para nosotros. Fue un gusto atenderle."
                . $firma,

            'invoiced' =>
                "{$saludo} *{$nombre}*,\n\n"
                . "Le saluda *{$taller}*. Le informamos que su orden de trabajo ha sido *facturada*.\n\n"
                . "{$vehiculo}{$ref}\n"
                . "Total facturado: *{$total}*\n\n"
                . "Muchas gracias por su preferencia. Fue un gusto atenderle y esperamos poder servirle nuevamente en el futuro."
                . $firma,
        ];

        return $messages[$newStatus] ?? "{$saludo} *{$nombre}*,\n\nLe saluda *{$taller}*. Le informamos que el estado de su vehiculo ha sido actualizado.\n\n{$vehiculo}{$ref}" . $firma;
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

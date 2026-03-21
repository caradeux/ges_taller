<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        private WorkOrder $workOrder,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $plate = strtoupper($this->workOrder->vehicle->license_plate ?? '');
        $brand = $this->workOrder->vehicle->brand ?? '';
        $model = $this->workOrder->vehicle->model ?? '';

        return (new MailMessage)
            ->subject("Vehículo Listo para Retiro — {$plate}")
            ->greeting("Estimado/a {$notifiable->name},")
            ->line("Nos complace informarle que su vehículo **{$plate} ({$brand} {$model})** está listo para ser retirado.")
            ->line("**OT N°:** {$this->workOrder->folio_display}")
            ->line('Por favor coordine el retiro en horario de atención (lunes a viernes).')
            ->line('¡Gracias por confiar en nuestro taller!')
            ->salutation('Saludos cordiales, el equipo del taller.');
    }
}

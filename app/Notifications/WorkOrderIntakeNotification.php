<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderIntakeNotification extends Notification
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
            ->subject("Confirmación de Ingreso — Vehículo {$plate}")
            ->greeting("Estimado/a {$notifiable->name},")
            ->line("Le confirmamos que su vehículo **{$plate} ({$brand} {$model})** ha ingresado a nuestro taller.")
            ->line("**OT N°:** {$this->workOrder->folio_display}")
            ->line("**Fecha de ingreso:** " . \Carbon\Carbon::parse($this->workOrder->date)->format('d/m/Y'))
            ->line('Le mantendremos informado sobre el avance de los trabajos.')
            ->salutation('Saludos cordiales, el equipo del taller.');
    }
}

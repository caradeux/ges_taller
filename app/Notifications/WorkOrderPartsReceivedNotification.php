<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderPartsReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private WorkOrder $workOrder,
        private string $partDescription = '',
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $plate = strtoupper($this->workOrder->vehicle->license_plate ?? '');

        return (new MailMessage)
            ->subject("Repuestos Recibidos — Vehículo {$plate}")
            ->greeting("Estimado/a {$notifiable->name},")
            ->line("Le informamos que los repuestos para su vehículo **{$plate}** han sido recibidos en nuestro taller.")
            ->when($this->partDescription, fn($mail) => $mail->line("**Repuesto:** {$this->partDescription}"))
            ->line("**OT N°:** {$this->workOrder->folio_display}")
            ->line('Procederemos con la reparación a la brevedad.')
            ->salutation('Saludos cordiales, el equipo del taller.');
    }
}

<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Appointment - Dr. ' . $notifiable->name)
            ->greeting('Hello Dr. ' . $notifiable->name . ',')
            ->line('A new appointment has been scheduled.')
            ->line('**Patient:** ' . $this->appointment->patient->name)
            ->line('**Date:** ' . $this->appointment->appointment_date->format('M d, Y'))
            ->line('**Time:** ' . $this->appointment->appointment_time->format('h:i A'))
            ->line('**Type:** ' . ucfirst($this->appointment->type))
            ->action('View Appointment', route('doctor.appointments.show', $this->appointment))
            ->line('Please log in to your dashboard for more details.');
    }
}

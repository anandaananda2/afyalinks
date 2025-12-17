<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelled extends Notification
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
        $refundMessage = '';
        if ($this->appointment->payment && $this->appointment->payment->status === 'refunded') {
            $refundMessage = 'A refund of KES ' . number_format($this->appointment->consultation_fee, 2) . ' has been processed and will reflect in your account within 3-5 business days.';
        }

        return (new MailMessage)
            ->subject('Appointment Cancelled - Afyalinks Health System')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your appointment has been cancelled.')
            ->line('**Cancelled Appointment Details:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->name)
            ->line('Date: ' . $this->appointment->appointment_date->format('l, F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time->format('h:i A'))
            ->line('Appointment Number: ' . $this->appointment->appointment_number)
            ->lineIf($refundMessage, $refundMessage)
            ->line('Cancellation Reason: ' . ($this->appointment->cancellation_reason ?? 'Not specified'))
            ->line('If you would like to reschedule, you can book a new appointment at any time.')
            ->action('Book New Appointment', route('patient.doctors.index'))
            ->line('Thank you for using Afyalinks Health System.');
    }
}
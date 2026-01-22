<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmation extends Notification
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
            ->subject('Appointment Confirmation - Afyalinks Health System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been confirmed.')
            ->line('**Appointment Details:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->name)
            ->line('Specialization: ' . $this->appointment->doctor->doctorProfile->specialization)
            ->line('Date: ' . $this->appointment->appointment_date->format('l, F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time->format('h:i A'))
            ->line('Duration: ' . $this->appointment->duration . ' minutes')
            ->line('Consultation Fee: KES ' . number_format($this->appointment->consultation_fee, 2))
            ->action('View Appointment', route('patient.appointments.show', $this->appointment))
            ->line('Please arrive 15 minutes before your scheduled time.')
            ->line('If you need to cancel or reschedule, please do so at least 2 hours before your appointment.')
            ->line('Thank you for choosing Afyalinks Health System!');
    }
}
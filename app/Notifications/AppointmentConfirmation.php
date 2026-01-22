<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Barryvdh\DomPDF\Facade\Pdf;

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
        $pdf = Pdf::loadView('pdf.receipt', ['appointment' => $this->appointment]);

        return (new MailMessage)
            ->subject('Appointment Confirmation - Afyalinks Health System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been confirmed.')
            ->line('**Appointment Details:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->name)
            ->line('Date: ' . $this->appointment->appointment_date->format('l, F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time->format('h:i A'))
            ->line('Please find your booking confirmation attached.')
            ->action('View Details', route('patient.appointments.show', $this->appointment))
            ->line('Payment is to be made on the appointment day.')
            ->attachData($pdf->output(), 'booking_confirmation.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification
{
    use Queueable;

    protected $appointment;
    protected $reminderType; // '24h' or '2h'

    public function __construct(Appointment $appointment, string $reminderType = '24h')
    {
        $this->appointment = $appointment;
        $this->reminderType = $reminderType;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $timeframe = $this->reminderType === '24h' ? '24 hours' : '2 hours';
        $subject = $this->reminderType === '24h' 
            ? 'Appointment Reminder - Tomorrow' 
            : 'Appointment Reminder - In 2 Hours';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder that you have an appointment in ' . $timeframe . '.')
            ->line('**Appointment Details:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->name)
            ->line('Specialization: ' . $this->appointment->doctor->doctorProfile->specialization)
            ->line('Date: ' . $this->appointment->appointment_date->format('l, F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time->format('h:i A'))
            ->line('Duration: ' . $this->appointment->duration . ' minutes')
            ->line('Location: Afyalinks Health Center');

        if ($this->reminderType === '2h') {
            $message->line('')
                    ->line('⏰ **Important:** Please arrive 15 minutes early for check-in.')
                    ->line('🚗 Plan your journey accordingly to avoid delays.');
        } else {
            $message->line('')
                    ->line('If you need to cancel or reschedule, please do so at least 2 hours before the appointment.');
        }

        return $message->action('View Appointment', route('patient.appointments.show', $this->appointment))
                       ->line('See you soon!');
    }
}
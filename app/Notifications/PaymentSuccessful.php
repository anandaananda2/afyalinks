<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessful extends Notification
{
    use Queueable;

    protected $payment;
    protected $appointment;

    public function __construct(Payment $payment, Appointment $appointment)
    {
        $this->payment = $payment;
        $this->appointment = $appointment;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Successful - Appointment Confirmed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment has been processed successfully.')
            ->line('**Payment Details:**')
            ->line('Transaction ID: ' . $this->payment->transaction_id)
            ->line('Amount Paid: KES ' . number_format($this->payment->amount, 2))
            ->line('Payment Method: ' . $this->payment->paymentMethodLabel)
            ->line('Payment Date: ' . $this->payment->paid_at->format('F d, Y h:i A'))
            ->line('')
            ->line('**Confirmed Appointment:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->name)
            ->line('Specialization: ' . $this->appointment->doctor->doctorProfile->specialization)
            ->line('Date: ' . $this->appointment->appointment_date->format('l, F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time->format('h:i A'))
            ->line('Duration: ' . $this->appointment->duration . ' minutes')
            ->line('')
            ->action('View Appointment', route('patient.appointments.show', $this->appointment))
            ->line('Please arrive 15 minutes before your scheduled time.')
            ->line('You will receive reminders 24 hours and 2 hours before your appointment.')
            ->action('Download Receipt', route('patient.payments.receipt', $this->payment))
            ->line('Thank you for choosing Afyalinks Health System!');
    }
}
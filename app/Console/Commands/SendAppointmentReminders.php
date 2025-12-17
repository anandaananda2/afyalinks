<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\AppointmentNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send appointment reminders to patients';

    public function handle()
    {
        $this->info('Checking for appointments requiring reminders...');

        // Get appointments for tomorrow (24-hour reminder)
        $tomorrow = now()->addDay()->toDateString();
        $appointmentsTomorrow = Appointment::where('appointment_date', $tomorrow)
            ->where('status', 'confirmed')
            ->whereDoesntHave('notifications', function($query) {
                $query->where('type', 'reminder_24h')
                      ->where('status', 'sent');
            })
            ->with('patient')
            ->get();

        foreach ($appointmentsTomorrow as $appointment) {
            $this->send24HourReminder($appointment);
        }

        $this->info("Sent {$appointmentsTomorrow->count()} 24-hour reminders.");

        // Get appointments in 2 hours
        $twoHoursFromNow = now()->addHours(2);
        $appointmentsIn2Hours = Appointment::whereDate('appointment_date', now()->toDateString())
            ->where('status', 'confirmed')
            ->whereRaw('TIME(appointment_time) BETWEEN ? AND ?', [
                $twoHoursFromNow->copy()->subMinutes(15)->format('H:i:s'),
                $twoHoursFromNow->copy()->addMinutes(15)->format('H:i:s')
            ])
            ->whereDoesntHave('notifications', function($query) {
                $query->where('type', 'reminder_2h')
                      ->where('status', 'sent');
            })
            ->with('patient')
            ->get();

        foreach ($appointmentsIn2Hours as $appointment) {
            $this->send2HourReminder($appointment);
        }

        $this->info("Sent {$appointmentsIn2Hours->count()} 2-hour reminders.");

        $this->info('Reminder sending complete!');
    }

    private function send24HourReminder(Appointment $appointment)
    {
        $message = "Reminder: You have an appointment tomorrow ({$appointment->appointment_date->format('M d, Y')}) at {$appointment->appointment_time->format('h:i A')} with Dr. {$appointment->doctor->name}. Location: Afyalinks Health Center.";

        // Send notification
        try {
            $appointment->patient->notify(new \App\Notifications\AppointmentReminder($appointment, '24h'));
        } catch (\Exception $e) {
            \Log::error('Failed to send 24h reminder: ' . $e->getMessage());
        }

        // Create notification record
        AppointmentNotification::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->patient_id,
            'type' => 'reminder_24h',
            'channel' => 'email',
            'status' => 'sent',
            'scheduled_for' => now(),
            'sent_at' => now(),
            'message' => $message,
        ]);

        $this->line("  → 24h reminder sent to {$appointment->patient->name}");
    }

    private function send2HourReminder(Appointment $appointment)
    {
        $message = "Reminder: Your appointment with Dr. {$appointment->doctor->name} is in 2 hours at {$appointment->appointment_time->format('h:i A')}. Please arrive 15 minutes early.";

        // Send notification
        try {
            $appointment->patient->notify(new \App\Notifications\AppointmentReminder($appointment, '2h'));
        } catch (\Exception $e) {
            \Log::error('Failed to send 2h reminder: ' . $e->getMessage());
        }

        // Create notification record
        AppointmentNotification::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->patient_id,
            'type' => 'reminder_2h',
            'channel' => 'email',
            'status' => 'sent',
            'scheduled_for' => now(),
            'sent_at' => now(),
            'message' => $message,
        ]);

        $this->line("  → 2h reminder sent to {$appointment->patient->name}");
    }

    private function sendNotification($user, $message)
    {
        // This method is now handled by the notification classes above
        // Kept for backward compatibility but not used
    }
}
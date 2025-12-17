<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckWaitingTimes extends Command
{
    protected $signature = 'appointments:check-waiting-times';
    protected $description = 'Check for excessive waiting times and alert health authorities';

    // Threshold in minutes for excessive waiting
    private const WAITING_TIME_THRESHOLD = 30;

    public function handle()
    {
        $this->info('Checking for excessive waiting times...');

        // Get today's checked-in appointments that haven't started
        $waitingAppointments = Appointment::whereDate('appointment_date', now()->toDateString())
            ->where('status', 'checked_in')
            ->whereNotNull('checked_in_at')
            ->whereNull('started_at')
            ->with(['patient', 'doctor'])
            ->get();

        $excessiveWaits = [];

        foreach ($waitingAppointments as $appointment) {
            $waitingMinutes = now()->diffInMinutes($appointment->checked_in_at);

            if ($waitingMinutes > self::WAITING_TIME_THRESHOLD) {
                $excessiveWaits[] = [
                    'appointment' => $appointment,
                    'waiting_time' => $waitingMinutes,
                ];

                $this->warn("  → Patient {$appointment->patient->name} waiting for {$waitingMinutes} minutes");
            }
        }

        if (count($excessiveWaits) > 0) {
            $this->alertHealthAuthorities($excessiveWaits);
            $this->error("Alert sent: {$this->count($excessiveWaits)} patient(s) waiting excessively.");
        } else {
            $this->info('No excessive waiting times detected.');
        }

        // Calculate and display average waiting time
        if ($waitingAppointments->count() > 0) {
            $avgWaitingTime = $waitingAppointments->avg(function($appointment) {
                return now()->diffInMinutes($appointment->checked_in_at);
            });

            $this->info("Average waiting time today: " . round($avgWaitingTime, 1) . " minutes");
        }
    }

    private function alertHealthAuthorities(array $excessiveWaits)
    {
        // Get admin and health worker users
        $authorities = User::whereIn('role', ['admin', 'health_worker'])->get();

        $message = "ALERT: " . count($excessiveWaits) . " patient(s) experiencing excessive waiting times (>" . self::WAITING_TIME_THRESHOLD . " minutes):\n\n";

        foreach ($excessiveWaits as $wait) {
            $message .= "- {$wait['appointment']->patient->name} with Dr. {$wait['appointment']->doctor->name}: {$wait['waiting_time']} minutes\n";
        }

        $message .= "\nImmediate action may be required.";

        // In production, send emails/SMS to authorities
        foreach ($authorities as $authority) {
            $this->sendAlert($authority, $message);
        }
    }

    private function sendAlert($user, $message)
    {
        // Simulate sending alert
        // In production:
        // - Mail::to($user->email)->send(new WaitingTimeAlert($message));
        // - Send SMS to $user->phone

        $this->line("  → Alert sent to {$user->name} ({$user->role})");
    }
}
<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Payment;
use App\Models\AppointmentNotification;
use App\Notifications\AppointmentConfirmation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = auth()->user()->patientAppointments()
            ->with('doctor.doctorProfile')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function create(Request $request): View
    {
        $doctorId = $request->query('doctor_id');
        $doctor = null;
        $availableSlots = [];

        if ($doctorId) {
            $doctor = User::where('role', 'doctor')
                ->where('id', $doctorId)
                ->with('doctorProfile')
                ->firstOrFail();

            // Get available time slots for the next 14 days
            $availableSlots = $this->getAvailableTimeSlots($doctor);
        }

        return view('patient.appointments.create', compact('doctor', 'availableSlots'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:users,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'type' => ['required', 'in:consultation,follow_up,emergency'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        // Get doctor and consultation fee
        $doctor = User::with('doctorProfile')->findOrFail($validated['doctor_id']);
        
        if (!$doctor->isDoctor()) {
            return back()->withErrors(['doctor_id' => 'Invalid doctor selected.']);
        }

        // Check if slot is still available
        $isAvailable = $this->checkSlotAvailability(
            $doctor,
            $validated['appointment_date'],
            $validated['appointment_time']
        );

        if (!$isAvailable) {
            return back()->withErrors(['appointment_time' => 'This time slot is no longer available.'])->withInput();
        }
        //Get doctor's consultation fee
        $doctor = User::findOrFail($request->doctor_id);
$consultationFee = $doctor->doctorProfile->consultation_fee ?? 0;

        // Create appointment
        $appointment = Appointment::create([
            'appointment_number' => 'APT' . str_pad(Appointment::count() + 1, 6, '0', STR_PAD_LEFT),
            'patient_id' => auth()->id(),
            'doctor_id' => $doctor->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'duration' => $doctor->doctorProfile->consultation_duration,
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'consultation_fee' => $consultationFee,
            'status' => 'confirmed',
            'payment_status' => 'pending',
        ]);

        // Send appointment confirmation notification
        try {
            // Send email to patient with PDF receipt
            auth()->user()->notify(new AppointmentConfirmation($appointment));
            
            // Send email to doctor
            $doctor->notify(new \App\Notifications\NewAppointmentNotification($appointment));
            
            // Create notification record
            AppointmentNotification::create([
                'appointment_id' => $appointment->id,
                'user_id' => auth()->id(),
                'type' => 'appointment_confirmation',
                'channel' => 'email',
                'status' => 'sent',
                'scheduled_for' => now(),
                'sent_at' => now(),
                'message' => 'Appointment confirmation for ' . $appointment->appointment_date->format('M d, Y'),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the appointment creation
            \Log::error('Failed to send appointment confirmation: ' . $e->getMessage());
        }

        return redirect()->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully! Payment will be made on the appointment day before seeing the doctor.');
    }

    public function show(Appointment $appointment): View
    {
        // Ensure patient can only view their own appointments
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        $appointment->load(['doctor.doctorProfile', 'payment']);

        return view('patient.appointments.show', compact('appointment'));
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        // Ensure patient can only cancel their own appointments
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$appointment->canBeCancelled()) {
            return back()->withErrors(['cancel' => 'This appointment cannot be cancelled. It must be cancelled at least 2 hours before the scheduled time.']);
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => request('reason', 'Cancelled by patient'),
        ]);

        // If payment was made, create refund
        if ($appointment->payment && $appointment->payment->status === 'completed') {
            $appointment->payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);
        }

        // Send cancellation notification
        try {
            auth()->user()->notify(new \App\Notifications\AppointmentCancelled($appointment));
            
            // Create notification record
            AppointmentNotification::create([
                'appointment_id' => $appointment->id,
                'user_id' => auth()->id(),
                'type' => 'appointment_cancelled',
                'channel' => 'email',
                'status' => 'sent',
                'scheduled_for' => now(),
                'sent_at' => now(),
                'message' => 'Appointment cancellation notification sent',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation notification: ' . $e->getMessage());
        }

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    // Helper method to get available time slots
    private function getAvailableTimeSlots(User $doctor): array
    {
        $slots = [];
        $today = now()->startOfDay();
        $endDate = now()->addDays(14);

        // Get doctor's availability
        $availabilities = $doctor->doctorAvailabilities()
            ->where('date', '>=', $today->toDateString())
            ->where('date', '<=', $endDate->toDateString())
            ->where('is_available', true)
            ->orderBy('date')
            ->get();

        // Get existing appointments
        $existingAppointments = $doctor->doctorAppointments()
            ->where('appointment_date', '>=', $today->toDateString())
            ->where('appointment_date', '<=', $endDate->toDateString())
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->groupBy('appointment_date');

        $consultationDuration = $doctor->doctorProfile->consultation_duration;

        foreach ($availabilities as $availability) {
            $date = $availability->date->format('Y-m-d');
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);

            $currentSlot = $startTime->copy();
            $dateSlots = [];

            while ($currentSlot->lt($endTime)) {
                $slotEnd = $currentSlot->copy()->addMinutes($consultationDuration);
                
                if ($slotEnd->lte($endTime)) {
                    $timeString = $currentSlot->format('H:i');
                    
                    // Check if slot is already booked
                    $isBooked = false;
                    if (isset($existingAppointments[$date])) {
                        foreach ($existingAppointments[$date] as $appointment) {
                            $appointmentStart = Carbon::parse($appointment->appointment_time);
                            $appointmentEnd = $appointmentStart->copy()->addMinutes($appointment->duration);
                            
                            if ($currentSlot->between($appointmentStart, $appointmentEnd, false) ||
                                $slotEnd->between($appointmentStart, $appointmentEnd, false)) {
                                $isBooked = true;
                                break;
                            }
                        }
                    }

                    if (!$isBooked) {
                        $dateSlots[] = [
                            'time' => $timeString,
                            'formatted' => $currentSlot->format('h:i A'),
                        ];
                    }
                }

                $currentSlot->addMinutes($consultationDuration);
            }

            if (!empty($dateSlots)) {
                $slots[$date] = [
                    'date' => $availability->date,
                    'formatted_date' => $availability->date->format('M d, Y (D)'),
                    'slots' => $dateSlots,
                ];
            }
        }

        return $slots;
    }

    // Helper method to check slot availability
    private function checkSlotAvailability(User $doctor, string $date, string $time): bool
    {
        $appointmentTime = Carbon::parse($time);
        $consultationDuration = $doctor->doctorProfile->consultation_duration;
        $appointmentEnd = $appointmentTime->copy()->addMinutes($consultationDuration);

        // Check if doctor has availability for this slot
        $hasAvailability = $doctor->doctorAvailabilities()
            ->where('date', $date)
            ->where('is_available', true)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $appointmentEnd->format('H:i:s'))
            ->exists();

        if (!$hasAvailability) {
            return false;
        }

        // Check for overlapping appointments
        $hasOverlap = $doctor->doctorAppointments()
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->where(function($query) use ($time, $appointmentEnd) {
                $query->whereBetween('appointment_time', [$time, $appointmentEnd->format('H:i:s')])
                    ->orWhere(function($q) use ($time, $appointmentEnd) {
                        $q->where('appointment_time', '<=', $time)
                          ->whereRaw('TIME(DATE_ADD(appointment_time, INTERVAL duration MINUTE)) > ?', [$time]);
                    });
            })
            ->exists();

        return !$hasOverlap;
    }
}
<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = auth()->user()->payments()
            ->with('appointment.doctor')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('patient.payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        // Ensure patient can only view their own payments
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        $payment->load(['appointment.doctor.doctorProfile']);

        return view('patient.payments.show', compact('payment'));
    }

    public function create(Appointment $appointment): View
    {
        // Ensure patient can only pay for their own appointments
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        // Check if already paid
        if ($appointment->payment_status === 'paid') {
            return redirect()->route('patient.appointments.show', $appointment)
                ->with('error', 'This appointment has already been paid.');
        }

        $appointment->load(['doctor.doctorProfile', 'payment']);

        return view('patient.payments.create', compact('appointment'));
    }

    public function process(Request $request, Appointment $appointment): RedirectResponse
    {
        // Ensure patient can only pay for their own appointments
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        // Check if already paid
        if ($appointment->payment_status === 'paid') {
            return redirect()->route('patient.appointments.show', $appointment)
                ->with('error', 'This appointment has already been paid.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:card,mobile_money,bank_transfer,cash'],
            'phone_number' => ['required_if:payment_method,mobile_money', 'nullable', 'string', 'max:20'],
        ]);

        DB::beginTransaction();

        try {
            $payment = $appointment->payment;

            if (!$payment) {
                // Create payment if doesn't exist
                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    'user_id' => auth()->id(),
                    'amount' => $appointment->consultation_fee,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'processing',
                ]);
            } else {
                // Update existing payment
                $payment->update([
                    'payment_method' => $validated['payment_method'],
                    'status' => 'processing',
                ]);
            }

            // Add phone number for mobile money
            if ($validated['payment_method'] === 'mobile_money' && isset($validated['phone_number'])) {
                $payment->update(['phone_number' => $validated['phone_number']]);
            }

            // Process payment based on method
            $result = $this->processPaymentByMethod($payment, $validated['payment_method']);

            if ($result['success']) {
                // Update payment status
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'provider_transaction_id' => $result['transaction_id'] ?? null,
                ]);

                // Update appointment payment status
                $appointment->update([
                    'payment_status' => 'paid',
                    'status' => $appointment->status === 'pending' ? 'confirmed' : $appointment->status,
                ]);

                // Send payment confirmation notification
                try {
                    auth()->user()->notify(new \App\Notifications\PaymentSuccessful($payment, $appointment));
                    
                    // Create notification record
                    \App\Models\AppointmentNotification::create([
                        'appointment_id' => $appointment->id,
                        'user_id' => auth()->id(),
                        'type' => 'appointment_confirmation',
                        'channel' => 'email',
                        'status' => 'sent',
                        'scheduled_for' => now(),
                        'sent_at' => now(),
                        'message' => 'Payment confirmation and appointment confirmation sent',
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send payment notification: ' . $e->getMessage());
                }

                DB::commit();

                return redirect()->route('patient.payments.show', $payment)
                    ->with('success', 'Payment successful! Your appointment has been confirmed.');
            } else {
                $payment->update([
                    'status' => 'failed',
                    'provider_response' => $result['message'] ?? 'Payment failed',
                ]);

                DB::commit();

                return back()->withErrors(['payment' => $result['message'] ?? 'Payment failed. Please try again.']);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['payment' => 'An error occurred while processing your payment. Please try again.']);
        }
    }

    // Simulate payment processing (replace with actual payment gateway integration)
    private function processPaymentByMethod(Payment $payment, string $method): array
    {
        // This is a simulation. In production, integrate with:
        // - M-Pesa API for mobile_money
        // - Stripe/Paystack for card payments
        // - Bank API for bank_transfer

        switch ($method) {
            case 'mobile_money':
                return $this->processMobileMoney($payment);
            
            case 'card':
                return $this->processCardPayment($payment);
            
            case 'bank_transfer':
                return $this->processBankTransfer($payment);
            
            case 'cash':
                // Cash payments are marked as pending until confirmed by admin/health worker
                return [
                    'success' => false,
                    'message' => 'Cash payment must be verified by staff. Please pay at the facility.',
                ];
            
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }

    private function processMobileMoney(Payment $payment): array
    {
        // Simulate M-Pesa STK Push
        // In production, integrate with Safaricom M-Pesa API
        
        // Simulate success (90% success rate for demo)
        if (rand(1, 10) <= 9) {
            return [
                'success' => true,
                'transaction_id' => 'MPESA' . strtoupper(uniqid()),
                'message' => 'M-Pesa payment successful',
            ];
        }

        return [
            'success' => false,
            'message' => 'M-Pesa payment failed. Please check your phone and try again.',
        ];
    }

    private function processCardPayment(Payment $payment): array
    {
        // Simulate card payment (Stripe/Paystack)
        // In production, integrate with Stripe, Paystack, or other card processor
        
        // Simulate success (95% success rate for demo)
        if (rand(1, 20) <= 19) {
            return [
                'success' => true,
                'transaction_id' => 'CARD' . strtoupper(uniqid()),
                'message' => 'Card payment successful',
            ];
        }

        return [
            'success' => false,
            'message' => 'Card payment declined. Please check your card details and try again.',
        ];
    }

    private function processBankTransfer(Payment $payment): array
    {
        // Bank transfers typically require manual verification
        // Mark as processing and notify admin
        
        $payment->update([
            'status' => 'processing',
            'notes' => 'Awaiting bank transfer confirmation',
        ]);

        return [
            'success' => false,
            'message' => 'Bank transfer initiated. Please complete the transfer and provide reference number to staff for verification.',
        ];
    }

    public function downloadReceipt(Payment $payment)
    {
        // Ensure patient can only download their own receipts
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status !== 'completed') {
            return back()->withErrors(['receipt' => 'Receipt is only available for completed payments.']);
        }

        $payment->load(['appointment.doctor.doctorProfile', 'appointment.patient']);

        // Generate PDF receipt (simplified version)
        // In production, use a proper PDF library like DomPDF or Snappy
        
        return view('patient.payments.receipt', compact('payment'));
    }
}
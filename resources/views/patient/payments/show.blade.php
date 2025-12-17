<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Payment Status Banner -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold">Payment #{{ $payment->transaction_id }}</h3>
                            <div class="mt-2">
                                <span class="px-3 py-1 text-sm rounded-full bg-{{ $payment->statusBadgeColor }}-100 text-{{ $payment->statusBadgeColor }}-800">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        </div>

                        @if($payment->status === 'completed')
                        <a href="{{ route('patient.payments.receipt', $payment) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Download Receipt
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Payment Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount Paid</label>
                                <p class="mt-1 text-3xl font-bold text-green-600">KES {{ number_format($payment->amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                                <p class="mt-1 text-gray-900 font-mono text-sm">{{ $payment->transaction_id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <p class="mt-1 text-gray-900">{{ $payment->paymentMethodLabel }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                                <p class="mt-1 text-gray-900">{{ $payment->created_at->format('l, F d, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->created_at->format('h:i A') }}</p>
                            </div>
                            @if($payment->paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Completed At</label>
                                <p class="mt-1 text-gray-900">{{ $payment->paid_at->format('h:i A, M d, Y') }}</p>
                            </div>
                            @endif
                            @if($payment->provider_transaction_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Provider Reference</label>
                                <p class="mt-1 text-gray-900 font-mono text-sm">{{ $payment->provider_transaction_id }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Appointment Details</h3>
                        
                        <div class="flex items-start space-x-4 mb-4">
                            @if($payment->appointment->doctor->doctorProfile->profile_photo)
                            <img src="{{ asset('storage/' . $payment->appointment->doctor->doctorProfile->profile_photo) }}" alt="{{ $payment->appointment->doctor->name }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($payment->appointment->doctor->name, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <h4 class="font-semibold text-lg">Dr. {{ $payment->appointment->doctor->name }}</h4>
                                <p class="text-gray-600">{{ $payment->appointment->doctor->doctorProfile->specialization }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-4 border-t">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Appointment Number</label>
                                <p class="mt-1 text-gray-900">{{ $payment->appointment->appointment_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                                <p class="mt-1 text-gray-900">{{ $payment->appointment->appointment_date->format('M d, Y') }} at {{ $payment->appointment->appointment_time->format('h:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($payment->appointment->type) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="mt-1 inline-block px-2 py-1 text-xs rounded-full bg-{{ $payment->appointment->statusBadgeColor }}-100 text-{{ $payment->appointment->statusBadgeColor }}-800">
                                    {{ ucfirst($payment->appointment->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('patient.appointments.show', $payment->appointment) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Full Appointment Details →
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('patient.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    Back to Payments
                </a>
                
                @if($payment->status === 'completed')
                <a href="{{ route('patient.payments.receipt', $payment) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Download Receipt
                </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
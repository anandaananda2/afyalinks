<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Appointment Status Banner -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold">Appointment #{{ $appointment->appointment_number }}</h3>
                            <div class="mt-2 flex items-center space-x-3">
                                <span class="px-3 py-1 text-sm rounded-full bg-{{ $appointment->statusBadgeColor }}-100 text-{{ $appointment->statusBadgeColor }}-800">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                                <span class="px-3 py-1 text-sm rounded-full {{ $appointment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    Payment: {{ ucfirst($appointment->payment_status) }}
                                </span>
                            </div>
                        </div>

                        @if($appointment->canBeCancelled())
                        <button onclick="cancelAppointment()" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600">
                            Cancel Appointment
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Doctor Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Doctor Information</h3>
                        <div class="flex items-start space-x-4">
                            @if($appointment->doctor->doctorProfile->profile_photo)
                            <img src="{{ asset('storage/' . $appointment->doctor->doctorProfile->profile_photo) }}" alt="{{ $appointment->doctor->name }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($appointment->doctor->name, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <h4 class="font-semibold text-lg">Dr. {{ $appointment->doctor->name }}</h4>
                                <p class="text-gray-600">{{ $appointment->doctor->doctorProfile->specialization }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $appointment->doctor->email }}</p>
                                @if($appointment->doctor->phone && $appointment->doctor->phone !== $appointment->doctor->email)
                                <p class="text-sm text-gray-500">{{ $appointment->doctor->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Appointment Details</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->appointment_date->format('l, F d, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Time</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->appointment_time->format('h:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->duration }} minutes</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($appointment->type) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason for Visit -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Reason for Visit</h3>
                        <p class="text-gray-700">{{ $appointment->reason }}</p>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Consultation Fee</label>
                                <p class="mt-1 text-2xl font-bold text-green-600">KES {{ number_format($appointment->consultation_fee, 2) }}</p>
                            </div>
                            
                            <div class="mt-4 p-4 bg-yellow-50 rounded-md border border-yellow-200">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Payment Information</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Payment will be made on the appointment day before seeing the doctor.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($appointment->payment && $appointment->payment->status === 'completed')
                            <div class="mt-4 border-t pt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                                    <p class="mt-1 text-gray-900 font-mono text-sm">{{ $appointment->payment->transaction_id }}</p>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                    <p class="mt-1 text-gray-900">{{ $appointment->payment->paymentMethodLabel }}</p>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm font-medium text-gray-700">Paid On</label>
                                    <p class="mt-1 text-gray-900">{{ $appointment->payment->paid_at ? $appointment->payment->paid_at->format('M d, Y h:i A') : '-' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Doctor's Notes (if available) -->
            @if($appointment->notes && $appointment->status === 'completed')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Doctor's Notes</h3>
                    <p class="text-gray-700">{{ $appointment->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('patient.appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    Back to Appointments
                </a>
                @if($appointment->status === 'completed')
                <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Download Receipt
                </a>
                @endif
            </div>

        </div>
    </div>

    <!-- Cancel Form -->
    <form id="cancel-form" method="POST" action="{{ route('patient.appointments.cancel', $appointment) }}" style="display: none;">
        @csrf
        <input type="hidden" name="reason" value="Cancelled by patient">
    </form>

    <script>
        function cancelAppointment() {
            if (confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')) {
                document.getElementById('cancel-form').submit();
            }
        }
    </script>
</x-app-layout>
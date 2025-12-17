<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Appointments') }}
            </h2>
            <a href="{{ route('patient.doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Book New Appointment
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->has('cancel'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <p class="text-sm text-red-700">{{ $errors->first('cancel') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if($appointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($appointments as $appointment)
                        <div class="border rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-semibold">Dr. {{ $appointment->doctor->name }}</h3>
                                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $appointment->statusBadgeColor }}-100 text-{{ $appointment->statusBadgeColor }}-800">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $appointment->payment_status === 'paid' ? 'Paid' : 'Payment Pending' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $appointment->doctor->doctorProfile->specialization ?? 'General Practice' }}</p>
                                    
                                    <div class="mt-3 grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-sm text-gray-500">Date & Time:</span>
                                            <p class="text-sm font-medium">
                                                {{ $appointment->appointment_date->format('M d, Y') }} at 
                                                {{ $appointment->appointment_time->format('h:i A') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Type:</span>
                                            <p class="text-sm font-medium">{{ ucfirst($appointment->type) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Duration:</span>
                                            <p class="text-sm font-medium">{{ $appointment->duration }} minutes</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Fee:</span>
                                            <p class="text-sm font-medium text-green-600">KES {{ number_format($appointment->consultation_fee, 2) }}</p>
                                        </div>
                                    </div>

                                    @if($appointment->reason)
                                    <div class="mt-3">
                                        <span class="text-sm text-gray-500">Reason:</span>
                                        <p class="text-sm">{{ $appointment->reason }}</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="ml-4 flex flex-col space-y-2">
                                    <a href="{{ route('patient.appointments.show', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 text-center">
                                        View Details
                                    </a>
                                    
                                    @if($appointment->canBeCancelled())
                                    <button onclick="cancelAppointment({{ $appointment->id }})" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 text-center">
                                        Cancel
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $appointments->links() }}
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by booking your first appointment.</p>
                        <div class="mt-6">
                            <a href="{{ route('patient.doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Find a Doctor
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <!-- Cancel Appointment Modal Form -->
    <form id="cancel-form" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="reason" value="Cancelled by patient">
    </form>

    <script>
        function cancelAppointment(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')) {
                const form = document.getElementById('cancel-form');
                form.action = `/patient/appointments/${appointmentId}/cancel`;
                form.submit();
            }
        }
    </script>
</x-app-layout>
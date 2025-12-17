<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ $user->name }}!</h3>
                    <p class="text-gray-600">Manage your appointments and health records</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('patient.doctors.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-6 rounded-lg shadow-sm transition">
                    <div class="text-3xl mb-2">📅</div>
                    <h4 class="font-semibold text-lg">Book Appointment</h4>
                    <p class="text-sm opacity-90">Schedule a consultation</p>
                </a>

                <a href="{{ route('patient.doctors.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-6 rounded-lg shadow-sm transition">
                    <div class="text-3xl mb-2">👨‍⚕️</div>
                    <h4 class="font-semibold text-lg">Find Doctors</h4>
                    <p class="text-sm opacity-90">Browse available specialists</p>
                </a>

                <a href="{{ route('patient.profile.show') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-6 rounded-lg shadow-sm transition">
                    <div class="text-3xl mb-2">📋</div>
                    <h4 class="font-semibold text-lg">My Profile</h4>
                    <p class="text-sm opacity-90">Update medical history</p>
                </a>
            </div>

            <!-- Pending Payments Alert -->
            @if($pendingPayments->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            You have <strong>{{ $pendingPayments->count() }}</strong> pending payment(s).
                            <a href="#" class="font-medium underline hover:text-yellow-600">View payments</a>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Upcoming Appointments</h3>
                        
                        @if($upcomingAppointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingAppointments as $appointment)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">Dr. {{ $appointment->doctor->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->doctor->doctorProfile->specialization ?? 'General Practice' }}</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                            {{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $appointment->statusBadgeColor }}-100 text-{{ $appointment->statusBadgeColor }}-800">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No upcoming appointments</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Appointments</h3>
                        
                        @if($recentAppointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentAppointments as $appointment)
                                <div class="border-l-4 border-gray-300 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">Dr. {{ $appointment->doctor->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->doctor->doctorProfile->specialization ?? 'General Practice' }}</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $appointment->appointment_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $appointment->statusBadgeColor }}-100 text-{{ $appointment->statusBadgeColor }}-800">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No past appointments</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
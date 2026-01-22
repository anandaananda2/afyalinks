<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doctor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, Dr. {{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $user->doctorProfile->specialization ?? 'General Practice' }}</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold">{{ $stats['today_total'] }}</div>
                    <div class="text-sm opacity-90">Today's Appointments</div>
                </div>

                <div class="bg-green-500 text-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold">{{ $stats['today_completed'] }}</div>
                    <div class="text-sm opacity-90">Completed Today</div>
                </div>

                <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold">{{ $stats['today_pending'] }}</div>
                    <div class="text-sm opacity-90">Pending Today</div>
                </div>

                <div class="bg-purple-500 text-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold">{{ $stats['upcoming_week'] }}</div>
                    <div class="text-sm opacity-90">This Week</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('doctor.availability.index') }}" class="bg-white hover:bg-gray-50 border-2 border-gray-200 p-6 rounded-lg shadow-sm transition">
                    <div class="text-3xl mb-2">📅</div>
                    <h4 class="font-semibold text-lg">Set Availability</h4>
                    <p class="text-sm text-gray-600">Manage your schedule</p>
                </a>

                <a href="{{ route('doctor.profile.show') }}" class="bg-white hover:bg-gray-50 border-2 border-gray-200 p-6 rounded-lg shadow-sm transition">
                    <div class="text-3xl mb-2">👤</div>
                    <h4 class="font-semibold text-lg">Update Profile</h4>
                    <p class="text-sm text-gray-600">Edit your information</p>
                </a>

                <a href="#" class="bg-white hover:bg-gray-50 border-2 border-gray-200 p-6 rounded-lg shadow-sm transition">
                    <div class="text-3xl mb-2">📊</div>
                    <h4 class="font-semibold text-lg">View Reports</h4>
                    <p class="text-sm text-gray-600">Appointment analytics</p>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Today's Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Today's Schedule</h3>
                            <a href="{{ route('doctor.appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        
                        @if($todayAppointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($todayAppointments as $appointment)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">{{ $appointment->patient->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->type }}</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $appointment->appointment_time->format('h:i A') }} ({{ $appointment->duration }} min)
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
                            <p class="text-gray-500 text-center py-8">No appointments scheduled for today</p>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Upcoming This Week</h3>
                        
                        @if($upcomingAppointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingAppointments->take(5) as $appointment)
                                <div class="border-l-4 border-green-500 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">{{ $appointment->patient->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->type }}</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time->format('h:i A') }}
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
                            <p class="text-gray-500 text-center py-8">No upcoming appointments this week</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
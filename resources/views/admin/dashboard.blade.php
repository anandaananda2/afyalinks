<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Total Patients</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_patients']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Total Doctors</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_doctors']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Total Appointments</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_appointments']) }}</p>
                                <p class="text-xs text-gray-500">Today: {{ $stats['today_appointments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-semibold text-green-600">KES {{ number_format($stats['total_revenue'], 2) }}</p>
                                <p class="text-xs text-gray-500">Today: {{ number_format($stats['today_revenue'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if($stats['critical_incidents'] > 0 || $stats['pending_appointments'] > 10)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @if($stats['critical_incidents'] > 0)
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <strong>{{ $stats['critical_incidents'] }}</strong> critical incident(s) require immediate attention.
                                <a href="{{ route('admin.incidents.index', ['severity' => 'critical']) }}" class="font-medium underline hover:text-red-600">View incidents</a>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if($stats['pending_appointments'] > 10)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>{{ $stats['pending_appointments'] }}</strong> appointments awaiting confirmation.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Recent Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Appointments</h3>
                        <div class="space-y-3">
                            @forelse($recentAppointments->take(5) as $appointment)
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="text-sm font-medium">{{ $appointment->patient->name }}</p>
                                    <p class="text-xs text-gray-500">with Dr. {{ $appointment->doctor->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm">{{ $appointment->appointment_date->format('M d') }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full bg-{{ $appointment->statusBadgeColor }}-100 text-{{ $appointment->statusBadgeColor }}-800">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No recent appointments</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Doctors -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Top Doctors by Appointments</h3>
                        <div class="space-y-3">
                            @forelse($topDoctors as $doctor)
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($doctor->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">Dr. {{ $doctor->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $doctor->doctorProfile->specialization ?? 'General' }}</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-blue-600">{{ $doctor->doctor_appointments_count }}</span>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Incidents -->
            @if($pendingIncidents->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Pending Incident Reports</h3>
                        <a href="{{ route('admin.incidents.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Severity</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reported By</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingIncidents as $incident)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $incident->item_name }}</td>
                                    <td class="px-4 py-2 text-sm">{{ ucfirst($incident->category) }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $incident->severityBadgeColor }}-100 text-{{ $incident->severityBadgeColor }}-800">
                                            {{ ucfirst($incident->severity) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm">{{ $incident->reporter->name }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $incident->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
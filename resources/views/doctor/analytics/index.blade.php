<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medical Reports & Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Completed Appointments</h3>
                        
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('doctor.analytics.index') }}" class="flex">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name..." class="rounded-l-md border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">Search</button>
                        </form>
                    </div>

                    @if($completedAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis / Notes</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($completedAppointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $appointment->appointment_date->format('M d, Y') }}<br>
                                            <span class="text-gray-500">{{ $appointment->appointment_time->format('h:i A') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $appointment->patient->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ucfirst($appointment->type) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $appointment->notes }}">
                                                {{ $appointment->notes ?: 'No notes recorded' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $appointment->completed_at ? $appointment->completed_at->format('M d, Y h:i A') : '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $completedAppointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No completed appointments found.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Appointment Header -->
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

                        <!-- Quick Status Update -->
                        @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                        <div>
                            <form method="POST" action="{{ route('doctor.appointments.updateStatus', $appointment) }}" class="inline-flex space-x-2">
                                @csrf
                                <select name="status" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirm</option>
                                    <option value="checked_in" {{ $appointment->status === 'checked_in' ? 'selected' : '' }}>Check In</option>
                                    <option value="in_progress" {{ $appointment->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Complete</option>
                                    <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                                </select>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Update Status
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Patient Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <p class="mt-1 text-gray-900 font-semibold">{{ $appointment->patient->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->patient->email }}</p>
                            </div>
                            @if($appointment->patient->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->patient->phone }}</p>
                            </div>
                            @endif
                            
                            @if($appointment->patient->patientProfile)
                            <div class="pt-3 border-t">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Medical History</label>
                                
                                @if($appointment->patient->patientProfile->blood_group)
                                <p class="text-sm"><strong>Blood Group:</strong> {{ $appointment->patient->patientProfile->blood_group }}</p>
                                @endif
                                
                                @if($appointment->patient->patientProfile->allergies)
                                <p class="text-sm mt-1"><strong>Allergies:</strong> {{ $appointment->patient->patientProfile->allergies }}</p>
                                @endif
                                
                                @if($appointment->patient->patientProfile->chronic_conditions)
                                <p class="text-sm mt-1"><strong>Chronic Conditions:</strong> {{ $appointment->patient->patientProfile->chronic_conditions }}</p>
                                @endif
                                
                                @if($appointment->patient->patientProfile->current_medications)
                                <p class="text-sm mt-1"><strong>Current Medications:</strong> {{ $appointment->patient->patientProfile->current_medications }}</p>
                                @endif
                            </div>
                            @endif
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
                                <p class="mt-1 text-gray-900">{{ $appointment->appointment_time->format('h:i A') }} - {{ $appointment->endTime->format('h:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->duration }} minutes</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($appointment->type) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Consultation Fee</label>
                                <p class="mt-1 text-xl font-bold text-green-600">KES {{ number_format($appointment->consultation_fee, 2) }}</p>
                            </div>
                            
                            @if($appointment->checked_in_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Checked In At</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->checked_in_at->format('h:i A') }}</p>
                            </div>
                            @endif
                            
                            @if($appointment->started_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Started At</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->started_at->format('h:i A') }}</p>
                            </div>
                            @endif
                            
                            @if($appointment->completed_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Completed At</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->completed_at->format('h:i A') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Reason for Visit -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Reason for Visit</h3>
                    <p class="text-gray-700">{{ $appointment->reason }}</p>
                </div>
            </div>

            <!-- Doctor's Notes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Diagnosis & Notes</h3>
                    
                    @if($appointment->notes)
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-700">{{ $appointment->notes }}</p>
                    </div>
                    @endif
@if($appointment->ai_trend)
<div class="alert alert-info mt-3">
    <h6>AI Health Trend Prediction</h6>
    <p class="mb-1"><strong>Trend:</strong> {{ $appointment->ai_trend }}</p>
    <p class="mb-0"><strong>Confidence:</strong> {{ $appointment->ai_confidence }}%</p>
</div>
@endif
                    <form method="POST" action="{{ route('doctor.appointments.addNotes', $appointment) }}">
                        @csrf
                        <textarea name="notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Add consultation notes, diagnosis, prescriptions, recommendations...">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div class="mt-4 flex items-center justify-between">
                            @if($appointment->status !== 'completed')
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="complete" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Mark as Completed</span>
                            </label>
                            @else
                            <div></div>
                            @endif

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ $appointment->notes ? 'Update Diagnosis' : 'Save Diagnosis' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end">
                <a href="{{ route('doctor.appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    Back to Appointments
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
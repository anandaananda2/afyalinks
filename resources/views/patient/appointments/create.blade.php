<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if($errors->has('appointment_time'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <p class="text-sm text-red-700">{{ $errors->first('appointment_time') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if($doctor)
                    <!-- Doctor Info -->
                    <div class="mb-6 pb-6 border-b">
                        <div class="flex items-center space-x-4">
                            @if($doctor->doctorProfile->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->doctorProfile->profile_photo) }}" alt="{{ $doctor->name }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($doctor->name, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <h3 class="text-xl font-semibold">Dr. {{ $doctor->name }}</h3>
                                <p class="text-gray-600">{{ $doctor->doctorProfile->specialization }}</p>
                                <p class="text-green-600 font-semibold">KES {{ number_format($doctor->doctorProfile->consultation_fee, 2) }} / {{ $doctor->doctorProfile->consultation_duration }} min</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('patient.appointments.store') }}">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <!-- Appointment Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Appointment Type *</label>
                            <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Type</option>
                                <option value="consultation" {{ old('type') === 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="follow_up" {{ old('type') === 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                <option value="emergency" {{ old('type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="mb-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Visit *</label>
                            <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe your symptoms or reason for consultation..." required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Available Slots -->
                        @if(!empty($availableSlots))
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Date & Time *</label>
                            
                            <div class="space-y-4 max-h-96 overflow-y-auto border rounded-lg p-4">
                                @foreach($availableSlots as $date => $dateInfo)
                                <div class="border-b pb-4 last:border-b-0">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $dateInfo['formatted_date'] }}</h4>
                                    <div class="grid grid-cols-3 md:grid-cols-4 gap-2">
                                        @foreach($dateInfo['slots'] as $slot)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="appointment_datetime" value="{{ $date }}|{{ $slot['time'] }}" class="peer sr-only" required>
                                            <div class="px-3 py-2 text-center border rounded-md peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:border-blue-400 transition">
                                                <span class="text-sm font-medium">{{ $slot['formatted'] }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @error('appointment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('appointment_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden fields for date and time -->
                        <input type="hidden" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}">
                        <input type="hidden" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}">

                        <script>
                            document.querySelectorAll('input[name="appointment_datetime"]').forEach(radio => {
                                radio.addEventListener('change', function() {
                                    const [date, time] = this.value.split('|');
                                    document.getElementById('appointment_date').value = date;
                                    document.getElementById('appointment_time').value = time;
                                });
                            });
                        </script>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('patient.doctors.show', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Book Appointment
                            </button>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No available time slots</h3>
                            <p class="mt-1 text-sm text-gray-500">This doctor doesn't have any available slots in the next 14 days.</p>
                            <div class="mt-6">
                                <a href="{{ route('patient.doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Find Another Doctor
                                </a>
                            </div>
                        </div>
                        @endif

                    </form>
                    @else
                    <!-- No doctor selected -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No doctor selected</h3>
                        <p class="mt-1 text-sm text-gray-500">Please select a doctor first to book an appointment.</p>
                        <div class="mt-6">
                            <a href="{{ route('patient.doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Browse Doctors
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
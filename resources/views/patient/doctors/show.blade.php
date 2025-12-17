<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doctor Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Doctor Profile Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        @if($doctor->doctorProfile->profile_photo)
                        <img src="{{ asset('storage/' . $doctor->doctorProfile->profile_photo) }}" alt="{{ $doctor->name }}" class="w-32 h-32 rounded-full object-cover">
                        @else
                        <div class="w-32 h-32 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold">
                            {{ substr($doctor->name, 0, 1) }}
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="text-3xl font-bold">Dr. {{ $doctor->name }}</h3>
                            <p class="text-xl text-gray-600 mt-1">{{ $doctor->doctorProfile->specialization }}</p>
                            <p class="text-sm text-gray-500 mt-2">License: {{ $doctor->doctorProfile->license_number }}</p>
                            
                            <div class="mt-4 flex items-center space-x-4">
                                <span class="px-3 py-1 rounded-full text-sm {{ $doctor->doctorProfile->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $doctor->doctorProfile->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                                <span class="text-2xl font-bold text-green-600">KES {{ number_format($doctor->doctorProfile->consultation_fee, 2) }}</span>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700">
                                    Book Appointment
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($doctor->doctorProfile->bio)
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold text-lg mb-2">About</h4>
                        <p class="text-gray-700">{{ $doctor->doctorProfile->bio }}</p>
                    </div>
                    @endif

                    @if($doctor->doctorProfile->qualifications)
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold text-lg mb-2">Qualifications</h4>
                        <p class="text-gray-700">{{ $doctor->doctorProfile->qualifications }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Consultation Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Consultation Details</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Consultation Fee</label>
                                <p class="mt-1 text-gray-900 text-xl font-bold text-green-600">KES {{ number_format($doctor->doctorProfile->consultation_fee, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Consultation Duration</label>
                                <p class="mt-1 text-gray-900">{{ $doctor->doctorProfile->consultation_duration }} minutes</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Working Hours</label>
                                <p class="mt-1 text-gray-900">
                                    {{ Carbon\Carbon::parse($doctor->doctorProfile->work_start_time)->format('h:i A') }} - 
                                    {{ Carbon\Carbon::parse($doctor->doctorProfile->work_end_time)->format('h:i A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Working Days</label>
                                <p class="mt-1 text-gray-900">{{ $doctor->doctorProfile->workingDaysText }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-gray-900">{{ $doctor->email }}</p>
                            </div>
                            @if($doctor->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="mt-1 text-gray-900">{{ $doctor->phone }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Available Time Slots -->
            @if($availabilities->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Upcoming Availability (Next 14 Days)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($availabilities as $availability)
                        <div class="border rounded-lg p-4">
                            <div class="font-semibold text-gray-900">{{ $availability->date->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-600">{{ $availability->date->format('l') }}</div>
                            <div class="mt-2 text-sm">
                                <span class="text-gray-700">
                                    {{ Carbon\Carbon::parse($availability->start_time)->format('h:i A') }} - 
                                    {{ Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}
                                </span>
                            </div>
                            <span class="mt-2 inline-block px-2 py-1 text-xs rounded-full {{ $availability->type === 'special' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($availability->type) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700">
                            Book an Appointment
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Upcoming Availability</h3>
                    <p class="text-gray-500">No availability slots posted for the next 14 days. Please check back later or contact the doctor directly.</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Profile') }}
            </h2>
            <a href="{{ route('doctor.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Edit Profile
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

            <!-- Profile Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        @if($profile->profile_photo)
                        <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover">
                        @else
                        <div class="w-32 h-32 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold">Dr. {{ $user->name }}</h3>
                            <p class="text-lg text-gray-600">{{ $profile->specialization }}</p>
                            <p class="text-sm text-gray-500 mt-2">License: {{ $profile->license_number }}</p>
                        </div>
                    </div>
                    @if($profile->bio)
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="font-semibold mb-2">About</h4>
                        <p class="text-gray-700">{{ $profile->bio }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Professional Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Professional Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Specialization</label>
                                <p class="mt-1 text-gray-900">{{ $profile->specialization }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">License Number</label>
                                <p class="mt-1 text-gray-900">{{ $profile->license_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Qualifications</label>
                                <p class="mt-1 text-gray-900">{{ $profile->qualifications ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contact Email</label>
                                <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contact Phone</label>
                                <p class="mt-1 text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultation Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Consultation Settings</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Consultation Fee</label>
                                <p class="mt-1 text-gray-900 text-2xl font-bold text-green-600">KES {{ number_format($profile->consultation_fee, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Consultation Duration</label>
                                <p class="mt-1 text-gray-900">{{ $profile->consultation_duration }} minutes</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Working Hours</label>
                                <p class="mt-1 text-gray-900">
                                    {{ Carbon\Carbon::parse($profile->work_start_time)->format('h:i A') }} - 
                                    {{ Carbon\Carbon::parse($profile->work_end_time)->format('h:i A') }}
                                </p>
                            </div>
                            @if($profile->break_start_time && $profile->break_end_time)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Break Time</label>
                                <p class="mt-1 text-gray-900">
                                    {{ Carbon\Carbon::parse($profile->break_start_time)->format('h:i A') }} - 
                                    {{ Carbon\Carbon::parse($profile->break_end_time)->format('h:i A') }}
                                </p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Working Days</label>
                                <p class="mt-1 text-gray-900">{{ $profile->workingDaysText }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Availability Status</label>
                                <span class="mt-1 inline-flex px-2 py-1 text-xs rounded-full {{ $profile->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $profile->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Profile') }}
            </h2>
            <a href="{{ route('patient.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
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

            <!-- Personal Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <p class="mt-1 text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <p class="mt-1 text-gray-900">{{ $profile->date_of_birth ? $profile->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <p class="mt-1 text-gray-900">{{ $profile->gender ? ucfirst($profile->gender) : 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Blood Group</label>
                            <p class="mt-1 text-gray-900">{{ $profile->blood_group ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">National ID</label>
                            <p class="mt-1 text-gray-900">{{ $profile->national_id ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <p class="mt-1 text-gray-900">{{ $profile->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Medical Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Allergies</label>
                            <p class="mt-1 text-gray-900">{{ $profile->allergies ?? 'None reported' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Chronic Conditions</label>
                            <p class="mt-1 text-gray-900">{{ $profile->chronic_conditions ?? 'None reported' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Medications</label>
                            <p class="mt-1 text-gray-900">{{ $profile->current_medications ?? 'None reported' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Past Surgeries</label>
                            <p class="mt-1 text-gray-900">{{ $profile->past_surgeries ?? 'None reported' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Emergency Contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Name</label>
                            <p class="mt-1 text-gray-900">{{ $profile->emergency_contact_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Phone</label>
                            <p class="mt-1 text-gray-900">{{ $profile->emergency_contact_phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Relationship</label>
                            <p class="mt-1 text-gray-900">{{ $profile->emergency_contact_relationship ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
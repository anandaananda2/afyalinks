<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Find a Doctor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('patient.doctors.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                            <select name="specialization" id="specialization" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Specializations</option>
                                @foreach($specializations as $spec)
                                <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="available_only" value="1" {{ request('available_only') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Available doctors only</span>
                            </label>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Search
                            </button>
                            <a href="{{ route('patient.doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Reset
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Doctors Grid -->
            @if($doctors->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($doctors as $doctor)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            @if($doctor->doctorProfile->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->doctorProfile->profile_photo) }}" alt="{{ $doctor->name }}" class="w-20 h-20 rounded-full object-cover">
                            @else
                            <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                                {{ substr($doctor->name, 0, 1) }}
                            </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold">Dr. {{ $doctor->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $doctor->doctorProfile->specialization }}</p>
                                <p class="text-sm font-semibold text-green-600 mt-1">KES {{ number_format($doctor->doctorProfile->consultation_fee, 2) }}</p>
                            </div>
                        </div>

                        @if($doctor->doctorProfile->bio)
                        <p class="mt-3 text-sm text-gray-700 line-clamp-2">{{ $doctor->doctorProfile->bio }}</p>
                        @endif

                        <div class="mt-4 flex items-center justify-between">
                            <span class="px-2 py-1 text-xs rounded-full {{ $doctor->doctorProfile->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $doctor->doctorProfile->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                            <a href="{{ route('patient.doctors.show', $doctor) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Profile →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $doctors->links() }}
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No doctors found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search filters.</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
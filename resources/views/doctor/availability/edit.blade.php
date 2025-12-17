<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Availability') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if($errors->has('time'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <p class="text-sm text-red-700">{{ $errors->first('time') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <form method="POST" action="{{ route('doctor.availability.update', $availability) }}">
                        @csrf
                        @method('PUT')

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date *</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $availability->date->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div class="mb-4">
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time *</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', Carbon\Carbon::parse($availability->start_time)->format('H:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div class="mb-4">
                            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time *</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', Carbon\Carbon::parse($availability->end_time)->format('H:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                            <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Type</option>
                                <option value="regular" {{ old('type', $availability->type) === 'regular' ? 'selected' : '' }}>Regular Hours</option>
                                <option value="special" {{ old('type', $availability->type) === 'special' ? 'selected' : '' }}>Special Hours</option>
                                <option value="leave" {{ old('type', $availability->type) === 'leave' ? 'selected' : '' }}>Leave / Unavailable</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                <strong>Regular:</strong> Normal working hours | 
                                <strong>Special:</strong> Extended or special availability | 
                                <strong>Leave:</strong> Mark as unavailable
                            </p>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Any additional information...">{{ old('notes', $availability->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('doctor.availability.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Update Availability
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
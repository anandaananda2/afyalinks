<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Professional Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Professional Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Profile Photo -->
                            <div class="md:col-span-2">
                                <label for="profile_photo" class="block text-sm font-medium text-gray-700">Profile Photo</label>
                                @if($profile->profile_photo)
                                <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Current Photo" class="mt-2 w-32 h-32 rounded-full object-cover">
                                @endif
                                <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-sm text-gray-500">Maximum file size: 2MB</p>
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Specialization -->
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization *</label>
                                <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $profile->specialization) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('specialization')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- License Number -->
                            <div>
                                <label for="license_number" class="block text-sm font-medium text-gray-700">License Number *</label>
                                <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $profile->license_number) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('license_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Qualifications -->
                            <div class="md:col-span-2">
                                <label for="qualifications" class="block text-sm font-medium text-gray-700">Qualifications</label>
                                <textarea name="qualifications" id="qualifications" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., MBBS, MD, PhD">{{ old('qualifications', $profile->qualifications) }}</textarea>
                                @error('qualifications')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bio -->
                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700">About / Bio</label>
                                <textarea name="bio" id="bio" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Brief introduction about yourself and your practice">{{ old('bio', $profile->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Consultation Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Consultation Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Consultation Fee -->
                            <div>
                                <label for="consultation_fee" class="block text-sm font-medium text-gray-700">Consultation Fee (KES) *</label>
                                <input type="number" name="consultation_fee" id="consultation_fee" min="0" step="0.01" value="{{ old('consultation_fee', $profile->consultation_fee) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('consultation_fee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Consultation Duration -->
                            <div>
                                <label for="consultation_duration" class="block text-sm font-medium text-gray-700">Consultation Duration (minutes) *</label>
                                <select name="consultation_duration" id="consultation_duration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="15" {{ old('consultation_duration', $profile->consultation_duration) == 15 ? 'selected' : '' }}>15 minutes</option>
                                    <option value="30" {{ old('consultation_duration', $profile->consultation_duration) == 30 ? 'selected' : '' }}>30 minutes</option>
                                    <option value="45" {{ old('consultation_duration', $profile->consultation_duration) == 45 ? 'selected' : '' }}>45 minutes</option>
                                    <option value="60" {{ old('consultation_duration', $profile->consultation_duration) == 60 ? 'selected' : '' }}>60 minutes</option>
                                    <option value="90" {{ old('consultation_duration', $profile->consultation_duration) == 90 ? 'selected' : '' }}>90 minutes</option>
                                </select>
                                @error('consultation_duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Work Start Time -->
                            <div>
                                <label for="work_start_time" class="block text-sm font-medium text-gray-700">Work Start Time *</label>
                                <input type="time" name="work_start_time" id="work_start_time" value="{{ old('work_start_time', $profile->work_start_time ? Carbon\Carbon::parse($profile->work_start_time)->format('H:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('work_start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Work End Time -->
                            <div>
                                <label for="work_end_time" class="block text-sm font-medium text-gray-700">Work End Time *</label>
                                <input type="time" name="work_end_time" id="work_end_time" value="{{ old('work_end_time', $profile->work_end_time ? Carbon\Carbon::parse($profile->work_end_time)->format('H:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('work_end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Break Start Time -->
                            <div>
                                <label for="break_start_time" class="block text-sm font-medium text-gray-700">Break Start Time (Optional)</label>
                                <input type="time" name="break_start_time" id="break_start_time" value="{{ old('break_start_time', $profile->break_start_time ? Carbon\Carbon::parse($profile->break_start_time)->format('H:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('break_start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Break End Time -->
                            <div>
                                <label for="break_end_time" class="block text-sm font-medium text-gray-700">Break End Time (Optional)</label>
                                <input type="time" name="break_end_time" id="break_end_time" value="{{ old('break_end_time', $profile->break_end_time ? Carbon\Carbon::parse($profile->break_end_time)->format('H:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('break_end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Working Days -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Working Days *</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @php
                                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                        $selectedDays = old('working_days', $profile->working_days ?? []);
                                    @endphp
                                    @foreach($days as $index => $day)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="working_days[]" value="{{ $index }}" {{ in_array($index, $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $day }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('working_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('doctor.profile.show') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
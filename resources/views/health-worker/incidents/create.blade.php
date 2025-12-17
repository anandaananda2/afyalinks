<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report New Incident') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <form method="POST" action="{{ route('health-worker.incidents.store') }}">
                        @csrf

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Category</option>
                                <option value="drugs" {{ old('category') === 'drugs' ? 'selected' : '' }}>Drugs/Medication</option>
                                <option value="equipment" {{ old('category') === 'equipment' ? 'selected' : '' }}>Equipment/Machinery</option>
                                <option value="supplies" {{ old('category') === 'supplies' ? 'selected' : '' }}>Medical Supplies</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Severity -->
                        <div class="mb-4">
                            <label for="severity" class="block text-sm font-medium text-gray-700">Severity *</label>
                            <select name="severity" id="severity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Severity</option>
                                <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low - Minor issue</option>
                                <option value="medium" {{ old('severity') === 'medium' ? 'selected' : '' }}>Medium - Moderate impact</option>
                                <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>High - Significant impact</option>
                                <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical - Urgent attention required</option>
                            </select>
                            @error('severity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Item Name -->
                        <div class="mb-4">
                            <label for="item_name" class="block text-sm font-medium text-gray-700">Item/Equipment Name *</label>
                            <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Paracetamol, X-Ray Machine, Syringes" required>
                            @error('item_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe the issue in detail..." required>{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Provide as much detail as possible about the shortage or failure.</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock Information (for drugs/supplies) -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="current_stock" class="block text-sm font-medium text-gray-700">Current Stock (Optional)</label>
                                <input type="number" name="current_stock" id="current_stock" value="{{ old('current_stock') }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                                <p class="mt-1 text-xs text-gray-500">Leave blank if not applicable</p>
                                @error('current_stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="minimum_required" class="block text-sm font-medium text-gray-700">Minimum Required (Optional)</label>
                                <input type="number" name="minimum_required" id="minimum_required" value="{{ old('minimum_required') }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                                <p class="mt-1 text-xs text-gray-500">Leave blank if not applicable</p>
                                @error('minimum_required')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4 pt-4 border-t">
                            <a href="{{ route('health-worker.incidents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                Submit Report
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
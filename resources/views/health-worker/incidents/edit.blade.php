<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Incident Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if($errors->has('edit'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <p class="text-sm text-red-700">{{ $errors->first('edit') }}</p>
            </div>
            @endif

            <!-- Report Info Banner -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-700">
                            <strong>Report #{{ $incident->report_number }}</strong>
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Reported on {{ $incident->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <form method="POST" action="{{ route('health-worker.incidents.update', $incident) }}">
                        @csrf
                        @method('PUT')

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Category</option>
                                <option value="drugs" {{ old('category', $incident->category) === 'drugs' ? 'selected' : '' }}>Drugs/Medication</option>
                                <option value="equipment" {{ old('category', $incident->category) === 'equipment' ? 'selected' : '' }}>Equipment/Machinery</option>
                                <option value="supplies" {{ old('category', $incident->category) === 'supplies' ? 'selected' : '' }}>Medical Supplies</option>
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
                                <option value="low" {{ old('severity', $incident->severity) === 'low' ? 'selected' : '' }}>Low - Minor issue</option>
                                <option value="medium" {{ old('severity', $incident->severity) === 'medium' ? 'selected' : '' }}>Medium - Moderate impact</option>
                                <option value="high" {{ old('severity', $incident->severity) === 'high' ? 'selected' : '' }}>High - Significant impact</option>
                                <option value="critical" {{ old('severity', $incident->severity) === 'critical' ? 'selected' : '' }}>Critical - Urgent attention required</option>
                            </select>
                            @error('severity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Item Name -->
                        <div class="mb-4">
                            <label for="item_name" class="block text-sm font-medium text-gray-700">Item/Equipment Name *</label>
                            <input type="text" name="item_name" id="item_name" value="{{ old('item_name', $incident->item_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Paracetamol, X-Ray Machine, Syringes" required>
                            @error('item_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea name="description" id="description" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe the issue in detail..." required>{{ old('description', $incident->description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Provide as much detail as possible about the shortage or failure.</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock Information (for drugs/supplies) -->
                        <div class="grid grid-cols-2 gap-4 mb-4" x-data="{ category: '{{ old('category', $incident->category) }}' }">
                            <div>
                                <label for="current_stock" class="block text-sm font-medium text-gray-700">Current Stock</label>
                                <input type="number" name="current_stock" id="current_stock" value="{{ old('current_stock', $incident->current_stock) }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                                <p class="mt-1 text-xs text-gray-500">Leave blank if not applicable</p>
                                @error('current_stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="minimum_required" class="block text-sm font-medium text-gray-700">Minimum Required</label>
                                <input type="number" name="minimum_required" id="minimum_required" value="{{ old('minimum_required', $incident->minimum_required) }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                                <p class="mt-1 text-xs text-gray-500">Leave blank if not applicable</p>
                                @error('minimum_required')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Status Display -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Current Status</h4>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 text-sm rounded-full 
                                    {{ $incident->status === 'resolved' || $incident->status === 'closed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $incident->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $incident->status === 'reported' || $incident->status === 'acknowledged' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    Last updated: {{ $incident->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <!-- Warning for Resolved Reports -->
                        @if(in_array($incident->status, ['resolved', 'closed']))
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-yellow-700">
                                    This report has been marked as {{ $incident->status }}. You may not be able to edit it.
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4 pt-4 border-t">
                            <a href="{{ route('health-worker.incidents.show', $incident) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Update Report
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Show/hide stock fields based on category
        document.getElementById('category').addEventListener('change', function() {
            const stockFields = document.querySelectorAll('[name="current_stock"], [name="minimum_required"]');
            const category = this.value;
            
            stockFields.forEach(field => {
                if (category === 'equipment') {
                    field.disabled = true;
                    field.value = '';
                    field.parentElement.classList.add('opacity-50');
                } else {
                    field.disabled = false;
                    field.parentElement.classList.remove('opacity-50');
                }
            });
        });

        // Trigger on page load
        document.getElementById('category').dispatchEvent(new Event('change'));
    </script>
    @endpush
</x-app-layout>
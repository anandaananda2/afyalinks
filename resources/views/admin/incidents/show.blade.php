<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Incident Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Report Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Report #{{ $incident->report_number }}</h3>
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($incident->category) }}
                                </span>
                                <span class="px-3 py-1 text-sm rounded-full bg-{{ $incident->severityBadgeColor }}-100 text-{{ $incident->severityBadgeColor }}-800">
                                    {{ ucfirst($incident->severity) }} Severity
                                </span>
                                <span class="px-3 py-1 text-sm rounded-full 
                                    {{ $incident->status === 'resolved' || $incident->status === 'closed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $incident->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $incident->status === 'reported' || $incident->status === 'acknowledged' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Quick Status Update -->
                        <div x-data="{ showForm: false }">
                            <button @click="showForm = !showForm" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Update Status
                            </button>

                            <div x-show="showForm" x-cloak class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
                                <div @click.away="showForm = false" class="bg-white rounded-lg p-6 max-w-md w-full">
                                    <h4 class="text-lg font-semibold mb-4">Update Incident Status</h4>
                                    <form method="POST" action="{{ route('admin.incidents.updateStatus', $incident) }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                <option value="acknowledged" {{ $incident->status === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                                <option value="in_progress" {{ $incident->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="resolved" {{ $incident->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="closed" {{ $incident->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Resolution Notes (Optional)</label>
                                            <textarea name="resolution_notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Add notes about the resolution...">{{ $incident->resolution_notes }}</textarea>
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Incident Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Incident Details</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Item/Equipment Name</label>
                                <p class="mt-1 text-gray-900 font-semibold text-lg">{{ $incident->item_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($incident->category) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Severity Level</label>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-sm rounded-full bg-{{ $incident->severityBadgeColor }}-100 text-{{ $incident->severityBadgeColor }}-800">
                                        {{ ucfirst($incident->severity) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reported Date</label>
                                <p class="mt-1 text-gray-900">{{ $incident->created_at->format('F d, Y h:i A') }}</p>
                                <p class="text-sm text-gray-500">{{ $incident->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                <p class="mt-1 text-gray-900">{{ $incident->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reporter & Stock Info -->
                <div class="space-y-6">
                    <!-- Reporter Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Reporter Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Reported By</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $incident->reporter->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Role</label>
                                    <p class="mt-1 text-gray-900">{{ ucfirst($incident->reporter->role) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact</label>
                                    <p class="mt-1 text-gray-900">{{ $incident->reporter->email }}</p>
                                    @if($incident->reporter->phone)
                                    <p class="text-sm text-gray-600">{{ $incident->reporter->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    @if($incident->category === 'drugs' || $incident->category === 'supplies')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Stock Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                                    <p class="mt-1 text-3xl font-bold {{ $incident->current_stock == 0 ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $incident->current_stock ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Minimum Required</label>
                                    <p class="mt-1 text-3xl font-bold text-gray-900">
                                        {{ $incident->minimum_required ?? 'N/A' }}
                                    </p>
                                </div>
                                @if($incident->current_stock !== null && $incident->minimum_required !== null)
                                <div class="pt-3 border-t">
                                    @php
                                        $percentage = ($incident->current_stock / $incident->minimum_required) * 100;
                                        $color = $percentage < 25 ? 'red' : ($percentage < 50 ? 'yellow' : 'green');
                                    @endphp
                                    <div class="bg-gray-200 rounded-full h-4">
                                        <div class="bg-{{ $color }}-500 h-4 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ round($percentage, 1) }}% of minimum required</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            <!-- Description -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Detailed Description</h3>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $incident->description }}</p>
                </div>
            </div>

            <!-- Resolution Notes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Resolution Notes</h3>
                    
                    @if($incident->resolution_notes)
                    <div class="bg-green-50 p-4 rounded-lg mb-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $incident->resolution_notes }}</p>
                        @if($incident->resolver)
                        <div class="mt-3 pt-3 border-t border-green-200">
                            <p class="text-sm text-gray-600">
                                Resolved by <strong>{{ $incident->resolver->name }}</strong> 
                                on {{ $incident->resolved_at->format('F d, Y h:i A') }}
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.incidents.addNotes', $incident) }}">
                        @csrf
                        <textarea name="resolution_notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Add resolution notes...">{{ $incident->resolution_notes }}</textarea>
                        <div class="mt-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ $incident->resolution_notes ? 'Update Notes' : 'Add Notes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.incidents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    Back to Incidents
                </a>
            </div>

        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
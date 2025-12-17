<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Report Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->has('edit'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <p class="text-sm text-red-700">{{ $errors->first('edit') }}</p>
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

                        @if(!in_array($incident->status, ['resolved', 'closed']))
                        <a href="{{ route('health-worker.incidents.edit', $incident) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Edit Report
                        </a>
                        @endif
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
                                <p class="mt-1 text-gray-900 font-semibold">{{ $incident->item_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($incident->category) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Severity Level</label>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $incident->severityBadgeColor }}-100 text-{{ $incident->severityBadgeColor }}-800">
                                        {{ ucfirst($incident->severity) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reported Date</label>
                                <p class="mt-1 text-gray-900">{{ $incident->created_at->format('F d, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $incident->created_at->format('h:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reported By</label>
                                <p class="mt-1 text-gray-900">{{ $incident->reporter->name }}</p>
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
                                <p class="mt-1 text-2xl font-bold {{ $incident->current_stock == 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $incident->current_stock ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Minimum Required</label>
                                <p class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ $incident->minimum_required ?? 'N/A' }}
                                </p>
                            </div>
                            @if($incident->current_stock !== null && $incident->minimum_required !== null)
                            <div class="pt-3 border-t">
                                <label class="block text-sm font-medium text-gray-700">Stock Status</label>
                                @php
                                    $percentage = ($incident->current_stock / $incident->minimum_required) * 100;
                                @endphp
                                <div class="mt-2">
                                    <div class="bg-gray-200 rounded-full h-4">
                                        <div class="bg-{{ $percentage < 25 ? 'red' : ($percentage < 50 ? 'yellow' : 'green') }}-500 h-4 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ round($percentage, 1) }}% of minimum required</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <!-- Status Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Status Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Status</label>
                                <p class="mt-1">
                                    <span class="px-3 py-2 text-sm rounded-full 
                                        {{ $incident->status === 'resolved' || $incident->status === 'closed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $incident->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $incident->status === 'reported' || $incident->status === 'acknowledged' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                    </span>
                                </p>
                            </div>
                            @if($incident->status === 'acknowledged')
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-800">✓ Your report has been acknowledged by the administration.</p>
                            </div>
                            @endif
                            @if($incident->status === 'in_progress')
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <p class="text-sm text-yellow-800">⏳ Resolution is in progress.</p>
                            </div>
                            @endif
                            @if($incident->status === 'resolved' || $incident->status === 'closed')
                            <div class="bg-green-50 p-3 rounded-lg">
                                <p class="text-sm text-green-800">✓ This incident has been resolved.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Description -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $incident->description }}</p>
                </div>
            </div>

            <!-- Resolution Notes (if available) -->
            @if($incident->resolution_notes)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Resolution Notes</h3>
                    <div class="bg-green-50 p-4 rounded-lg">
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
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('health-worker.incidents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    Back to Reports
                </a>
                
                @if(!in_array($incident->status, ['resolved', 'closed']))
                <a href="{{ route('health-worker.incidents.edit', $incident) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Edit Report
                </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
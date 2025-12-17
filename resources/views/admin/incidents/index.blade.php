<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Reports Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600">Total Reports</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg shadow">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                    <div class="text-sm text-gray-600">Pending</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg shadow">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</div>
                    <div class="text-sm text-gray-600">In Progress</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg shadow">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</div>
                    <div class="text-sm text-gray-600">Resolved</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg shadow">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['critical'] }}</div>
                    <div class="text-sm text-gray-600">Critical Open</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.incidents.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="reported" {{ request('status') == 'reported' ? 'selected' : '' }}>Reported</option>
                                <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Categories</option>
                                <option value="drugs" {{ request('category') == 'drugs' ? 'selected' : '' }}>Drugs</option>
                                <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="supplies" {{ request('category') == 'supplies' ? 'selected' : '' }}>Supplies</option>
                            </select>
                        </div>

                        <div>
                            <label for="severity" class="block text-sm font-medium text-gray-700">Severity</label>
                            <select name="severity" id="severity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Severities</option>
                                <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Filter
                            </button>
                            <a href="{{ route('admin.incidents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Reset
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if($reports->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reports as $report)
                                <tr class="hover:bg-gray-50 {{ $report->severity === 'critical' && !in_array($report->status, ['resolved', 'closed']) ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-mono text-gray-900">{{ $report->report_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $report->item_name }}</div>
                                        <div class="text-xs text-gray-500 line-clamp-1">{{ $report->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($report->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $report->severityBadgeColor }}-100 text-{{ $report->severityBadgeColor }}-800">
                                            {{ ucfirst($report->severity) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $report->status === 'resolved' || $report->status === 'closed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $report->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $report->status === 'reported' || $report->status === 'acknowledged' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $report->reporter->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $report->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.incidents.show', $report) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No incident reports found</h3>
                        <p class="mt-1 text-sm text-gray-500">No incidents match your current filters.</p>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
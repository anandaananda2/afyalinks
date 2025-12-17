<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IncidentReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = IncidentReport::with(['reporter', 'resolver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $stats = [
            'total' => IncidentReport::count(),
            'pending' => IncidentReport::whereIn('status', ['reported', 'acknowledged'])->count(),
            'in_progress' => IncidentReport::where('status', 'in_progress')->count(),
            'resolved' => IncidentReport::where('status', 'resolved')->count(),
            'critical' => IncidentReport::where('severity', 'critical')->whereNotIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('admin.incidents.index', compact('reports', 'stats'));
    }

    public function show(IncidentReport $incident): View
    {
        $incident->load(['reporter', 'resolver']);

        return view('admin.incidents.show', compact('incident'));
    }

    public function updateStatus(Request $request, IncidentReport $incident): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:acknowledged,in_progress,resolved,closed'],
            'resolution_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'resolved' || $validated['status'] === 'closed') {
            $updateData['resolved_by'] = auth()->id();
            $updateData['resolved_at'] = now();
            if (isset($validated['resolution_notes'])) {
                $updateData['resolution_notes'] = $validated['resolution_notes'];
            }
        }

        $incident->update($updateData);

        return back()->with('success', 'Incident status updated successfully!');
    }

    public function addNotes(Request $request, IncidentReport $incident): RedirectResponse
    {
        $validated = $request->validate([
            'resolution_notes' => ['required', 'string', 'max:1000'],
        ]);

        $incident->update($validated);

        return back()->with('success', 'Notes added successfully!');
    }
}

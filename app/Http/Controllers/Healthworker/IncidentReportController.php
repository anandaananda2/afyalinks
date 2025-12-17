<?php

namespace App\Http\Controllers\HealthWorker;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IncidentReportController extends Controller
{
    public function index(): View
    {
        $reports = auth()->user()->incidentReports()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('health-worker.incidents.index', compact('reports'));
    }

    public function create(): View
    {
        return view('health-worker.incidents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:drugs,equipment,supplies'],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'current_stock' => ['nullable', 'integer', 'min:0'],
            'minimum_required' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['reported_by'] = auth()->id();
        $validated['status'] = 'reported';

        IncidentReport::create($validated);

        return redirect()->route('health-worker.incidents.index')
            ->with('success', 'Incident report submitted successfully!');
    }

    public function show(IncidentReport $incident): View
    {
        // Ensure health worker can only view their own reports
        if ($incident->reported_by !== auth()->id()) {
            abort(403);
        }

        $incident->load(['reporter', 'resolver']);

        return view('health-worker.incidents.show', compact('incident'));
    }

    public function edit(IncidentReport $incident): View
    {
        // Ensure health worker can only edit their own reports
        if ($incident->reported_by !== auth()->id()) {
            abort(403);
        }

        // Can only edit if not yet resolved
        if (in_array($incident->status, ['resolved', 'closed'])) {
            return redirect()->route('health-worker.incidents.show', $incident)
                ->withErrors(['edit' => 'Cannot edit resolved or closed incidents.']);
        }

        return view('health-worker.incidents.edit', compact('incident'));
    }

    public function update(Request $request, IncidentReport $incident): RedirectResponse
    {
        // Ensure health worker can only update their own reports
        if ($incident->reported_by !== auth()->id()) {
            abort(403);
        }

        // Can only update if not yet resolved
        if (in_array($incident->status, ['resolved', 'closed'])) {
            return redirect()->route('health-worker.incidents.show', $incident)
                ->withErrors(['edit' => 'Cannot update resolved or closed incidents.']);
        }

        $validated = $request->validate([
            'category' => ['required', 'in:drugs,equipment,supplies'],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'current_stock' => ['nullable', 'integer', 'min:0'],
            'minimum_required' => ['nullable', 'integer', 'min:0'],
        ]);

        $incident->update($validated);

        return redirect()->route('health-worker.incidents.show', $incident)
            ->with('success', 'Incident report updated successfully!');
    }
}

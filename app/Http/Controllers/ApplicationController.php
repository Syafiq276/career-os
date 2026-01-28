<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Display a listing of job applications.
     * 
     * Shows all applications for the authenticated user sorted by most recent first.
     * Includes filtering by status if query parameter is present.
     */
    public function index(Request $request): View
    {
        $query = auth()->user()->applications();

        // Apply filters using scopes for cleaner code
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Use scope for ordering and paginate
        $applications = $query->recent()->paginate(15);

        $statusCounts = auth()->user()
            ->applications()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalApplications = $statusCounts->sum();
        $applied = (int) ($statusCounts[Application::STATUS_APPLIED] ?? 0);
        $screening = (int) ($statusCounts[Application::STATUS_SCREENING] ?? 0);
        $interview = (int) ($statusCounts[Application::STATUS_INTERVIEW] ?? 0);
        $offer = (int) ($statusCounts[Application::STATUS_OFFER] ?? 0);
        $rejected = (int) ($statusCounts[Application::STATUS_REJECTED] ?? 0);
        $ghosted = (int) ($statusCounts[Application::STATUS_GHOSTED] ?? 0);

        $active = $applied + $screening + $interview + $offer;

        $responseRate = $totalApplications > 0
            ? round((($interview + $offer + $rejected) / $totalApplications) * 100)
            : 0;

        $interviewRate = $totalApplications > 0
            ? round(($interview / $totalApplications) * 100)
            : 0;

        $offerRate = $totalApplications > 0
            ? round(($offer / $totalApplications) * 100)
            : 0;

        $ghostedRate = $totalApplications > 0
            ? round(($ghosted / $totalApplications) * 100)
            : 0;

        return view('applications.index', [
            'applications' => $applications,
            'statuses' => Application::getStatuses(),
            'statusCounts' => $statusCounts,
            'totalApplications' => $totalApplications,
            'activeApplications' => $active,
            'responseRate' => $responseRate,
            'interviewRate' => $interviewRate,
            'offerRate' => $offerRate,
            'ghostedRate' => $ghostedRate,
        ]);
    }

    /**
     * Show the form for creating a new application.
     */
    public function create(): View
    {
        return view('applications.create', [
            'statuses' => Application::getStatuses(),
        ]);
    }

    /**
     * Store a newly created application in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'job_link' => 'nullable|url|max:500',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', Application::getStatuses()),
            'applied_at' => 'required|date',
            'interview_at' => 'nullable|date|after_or_equal:applied_at',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->applications()->create($validated);

        return redirect()->route('applications.index')
                        ->with('success', 'Application added successfully!');
    }

    /**
     * Display the specified application.
     */
    public function show(Application $application): View
    {
        // Ensure user can only view their own applications
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        return view('applications.show', [
            'application' => $application,
        ]);
    }

    /**
     * Show the form for editing the specified application.
     */
    public function edit(Application $application): View
    {
        // Ensure user can only edit their own applications
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        return view('applications.edit', [
            'application' => $application,
            'statuses' => Application::getStatuses(),
        ]);
    }

    /**
     * Update the specified application in the database.
     */
    public function update(Request $request, Application $application): RedirectResponse
    {
        // Ensure user can only update their own applications
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'job_link' => 'nullable|url|max:500',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', Application::getStatuses()),
            'applied_at' => 'required|date',
            'interview_at' => 'nullable|date|after_or_equal:applied_at',
            'notes' => 'nullable|string',
        ]);

        $application->update($validated);

        return redirect()->route('applications.index')
                        ->with('success', 'Application updated successfully!');
    }

    /**
     * Remove the specified application from the database.
     */
    public function destroy(Application $application): RedirectResponse
    {
        // Ensure user can only delete their own applications
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->delete();

        return redirect()->route('applications.index')
                        ->with('success', 'Application deleted successfully!');
    }
}

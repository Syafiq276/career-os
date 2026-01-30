@extends('layouts.app')

@section('title', 'All Applications')

@section('content')
<style>
    .vscode-wrap { background: #1e1e1e; border: 1px solid #2a2a2a; }
    .vscode-title { color: #d4d4d4; font-family: "Consolas", "Fira Code", monospace; }
    .vscode-sub { color: #9da5b4; }
    .vscode-card { background: #252526; border: 1px solid #2a2a2a; }
    .vscode-pill { background: #2d2d2d; border: 1px solid #3c3c3c; color: #d4d4d4; }
    .vscode-input { background: #1f1f1f; border: 1px solid #3c3c3c; color: #d4d4d4; }
    .vscode-input:focus { outline: none; box-shadow: 0 0 0 2px rgba(0, 122, 204, 0.35); border-color: #007acc; }
    .vscode-btn { background: #007acc; color: #fff; }
    .vscode-btn:hover { background: #1f8ad2; }
    .vscode-btn-secondary { background: #2d2d2d; color: #d4d4d4; }
    .vscode-btn-secondary:hover { background: #3c3c3c; }
    .vscode-table thead { background: #2d2d2d; }
    .vscode-table tbody { background: #252526; }
    .vscode-table td, .vscode-table th { color: #d4d4d4; }
</style>

<div class="vscode-wrap rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
    <h2 class="text-2xl sm:text-3xl font-bold vscode-title">Job Applications</h2>
    <p class="text-sm sm:text-base vscode-sub mt-1">Track and manage your job search journey</p>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
    <div class="vscode-card rounded-lg p-4">
        <div class="text-sm text-slate-400 mb-1">Total Applications</div>
        <div class="text-2xl font-bold text-white">{{ $totalApplications }}</div>
    </div>
    <div class="vscode-card rounded-lg p-4">
        <div class="text-sm text-slate-400 mb-1">Active</div>
        <div class="text-2xl font-bold text-blue-400">{{ $activeApplications }}</div>
    </div>
    <div class="vscode-card rounded-lg p-4">
        <div class="text-sm text-slate-400 mb-1">Interviews</div>
        <div class="text-2xl font-bold text-purple-400">{{ $statusCounts['interview'] ?? 0 }}</div>
    </div>
    <div class="vscode-card rounded-lg p-4">
        <div class="text-sm text-slate-400 mb-1">Offers</div>
        <div class="text-2xl font-bold text-green-400">{{ $statusCounts['offer'] ?? 0 }}</div>
    </div>
</div>

<!-- Analysis Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
    <div class="vscode-card rounded-lg p-4 sm:p-6 lg:col-span-2">
        <h3 class="text-lg font-semibold text-white mb-4">Application Funnel</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-blue-300 uppercase">Applied</div>
                <div class="text-xl font-bold text-blue-200">{{ $statusCounts['applied'] ?? 0 }}</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-yellow-300 uppercase">Screening</div>
                <div class="text-xl font-bold text-yellow-200">{{ $statusCounts['screening'] ?? 0 }}</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-purple-300 uppercase">Interview</div>
                <div class="text-xl font-bold text-purple-200">{{ $statusCounts['interview'] ?? 0 }}</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-green-300 uppercase">Offer</div>
                <div class="text-xl font-bold text-green-200">{{ $statusCounts['offer'] ?? 0 }}</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-red-300 uppercase">Rejected</div>
                <div class="text-xl font-bold text-red-200">{{ $statusCounts['rejected'] ?? 0 }}</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-slate-300 uppercase">Ghosted</div>
                <div class="text-xl font-bold text-slate-200">{{ $statusCounts['ghosted'] ?? 0 }}</div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-slate-300 uppercase">Response Rate</div>
                <div class="text-lg font-bold text-white">{{ $responseRate }}%</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-slate-300 uppercase">Interview Rate</div>
                <div class="text-lg font-bold text-white">{{ $interviewRate }}%</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-slate-300 uppercase">Offer Rate</div>
                <div class="text-lg font-bold text-white">{{ $offerRate }}%</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-slate-300 uppercase">Ghosted Rate</div>
                <div class="text-lg font-bold text-white">{{ $ghostedRate }}%</div>
            </div>
            <div class="p-3 vscode-pill rounded-lg">
                <div class="text-xs text-slate-300 uppercase">Avg Response</div>
                <div class="text-lg font-bold text-white">{{ $avgResponseDays }}d</div>
            </div>
        </div>
    </div>

    <div class="vscode-card rounded-lg p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Improvement Notes</h3>
        <ul class="space-y-3 text-sm text-slate-300">
            @if($ghostedRate >= 40)
                <li class="flex gap-2">
                    <span class="text-gray-500">•</span>
                    Follow up within 7–10 days after applying. A short, polite nudge can lift response rates.
                </li>
            @endif
            @if($interviewRate < 20)
                <li class="flex gap-2">
                    <span class="text-gray-500">•</span>
                    Tailor your resume to each role and mirror keywords from the job description.
                </li>
            @endif
            @if($offerRate > 0)
                <li class="flex gap-2">
                    <span class="text-gray-500">•</span>
                    You’re getting offers—prepare a negotiation script and a target range before interviews.
                </li>
            @endif
            <li class="flex gap-2">
                <span class="text-gray-500">•</span>
                Practice 2–3 stories using STAR (Situation, Task, Action, Result) for common behavioral questions.
            </li>
            <li class="flex gap-2">
                <span class="text-gray-500">•</span>
                Track recruiter touchpoints in your notes to identify what messaging works best.
            </li>
        </ul>
    </div>
</div>

<!-- Filters & Search -->
<div class="vscode-card rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
    <form method="GET" action="{{ route('applications.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
        
        <!-- Search Box -->
        <div class="flex-1 min-w-[200px]">
                 <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="🔍 Search company or job title..."
                     class="vscode-input w-full px-4 py-2 rounded-lg">
        </div>

        <!-- Status Filter -->
        <div class="min-w-[180px]">
                <select name="status" 
                    class="vscode-input w-full px-4 py-2 rounded-lg">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buttons -->
        <button type="submit" class="vscode-btn px-6 py-2 rounded-lg transition">
            Filter
        </button>
        <a href="{{ route('applications.index') }}" class="vscode-btn-secondary px-6 py-2 rounded-lg transition">
            Clear
        </a>
    </form>
</div>

<!-- Applications Table -->
@if($applications->count() > 0)
    <div class="vscode-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
        <table class="vscode-table min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Job Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Applied Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Response Time</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($applications as $application)
                    <tr class="hover:bg-[#2d2d2d] transition">
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-100 text-sm sm:text-base">{{ $application->company_name }}</div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="text-xs sm:text-sm text-slate-100">{{ $application->job_title }}</div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                            {{ $application->location ?? 'N/A' }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="status-badge text-xs px-2 py-1 rounded-full font-semibold
                                @if($application->status === 'applied') bg-blue-100 text-blue-800
                                @elseif($application->status === 'screening') bg-yellow-100 text-yellow-800
                                @elseif($application->status === 'interview') bg-purple-100 text-purple-800
                                @elseif($application->status === 'offer') bg-green-100 text-green-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-400">
                            <span class="hidden sm:inline">{{ $application->applied_at->format('M d, Y') }}</span>
                            <span class="sm:hidden">{{ $application->applied_at->format('m/d') }}</span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-400">
                            @if($application->response_days !== null)
                                {{ $application->response_days }} days
                            @else
                                <span class="text-gray-400">Pending</span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                            <div class="flex justify-end gap-1 sm:gap-2">
                                <a href="{{ route('applications.show', $application) }}" 
                                   class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="{{ route('applications.edit', $application) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 hidden sm:inline">Edit</a>
                                <form method="POST" 
                                      action="{{ route('applications.destroy', $application) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this application?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $applications->links() }}
    </div>

@else
    <div class="vscode-card rounded-lg p-12 text-center">
        <p class="text-slate-300 text-lg mb-4">No applications found</p>
        <a href="{{ route('applications.create') }}" 
           class="inline-block vscode-btn px-6 py-3 rounded-lg transition">
            ➕ Add Your First Application
        </a>
    </div>
@endif

@endsection

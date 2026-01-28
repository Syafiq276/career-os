@extends('layouts.app')

@section('title', 'View Application')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header with Actions -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">{{ $application->job_title }}</h2>
            <p class="text-xl text-gray-600 mt-1">{{ $application->company_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('applications.edit', $application) }}" 
               class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
                ✏️ Edit
            </a>
            <form method="POST" 
                  action="{{ route('applications.destroy', $application) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this application?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition">
                    🗑️ Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Application Details Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        
        <!-- Status Banner -->
        <div class="px-8 py-4 border-b border-gray-200 
            @if($application->status === 'applied') bg-blue-50
            @elseif($application->status === 'screening') bg-yellow-50
            @elseif($application->status === 'interview') bg-purple-50
            @elseif($application->status === 'offer') bg-green-50
            @elseif($application->status === 'rejected') bg-red-50
            @else bg-gray-50
            @endif">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-600">Current Status:</span>
                <span class="status-badge 
                    @if($application->status === 'applied') bg-blue-100 text-blue-800
                    @elseif($application->status === 'screening') bg-yellow-100 text-yellow-800
                    @elseif($application->status === 'interview') bg-purple-100 text-purple-800
                    @elseif($application->status === 'offer') bg-green-100 text-green-800
                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($application->status) }}
                </span>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="px-8 py-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Company Name -->
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Company</label>
                <p class="text-gray-900 text-lg font-semibold">{{ $application->company_name }}</p>
            </div>

            <!-- Job Title -->
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Job Title</label>
                <p class="text-gray-900 text-lg font-semibold">{{ $application->job_title }}</p>
            </div>

            <!-- Location -->
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Location</label>
                <p class="text-gray-900">{{ $application->location ?? 'Not specified' }}</p>
            </div>

            <!-- Salary Range -->
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Salary Range</label>
                <p class="text-gray-900">{{ $application->salary_range ?? 'Not specified' }}</p>
            </div>

            <!-- Applied Date -->
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Applied Date</label>
                <p class="text-gray-900">{{ $application->applied_at->format('F j, Y') }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $application->applied_at->diffForHumans() }}</p>
            </div>

            <!-- Interview Date -->
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Interview Date</label>
                @if($application->interview_at)
                    <p class="text-gray-900">{{ $application->interview_at->format('F j, Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $application->interview_at->diffForHumans() }}</p>
                @else
                    <p class="text-gray-400 italic">Not scheduled</p>
                @endif
            </div>

            <!-- Job Link -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 block mb-1">Job Posting Link</label>
                @if($application->job_link)
                    <a href="{{ $application->job_link }}" 
                       target="_blank" 
                       class="text-blue-600 hover:text-blue-800 hover:underline break-all">
                        {{ $application->job_link }} 🔗
                    </a>
                @else
                    <p class="text-gray-400 italic">No link provided</p>
                @endif
            </div>

            <!-- Notes -->
            @if($application->notes)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500 block mb-2">Notes</label>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-700 whitespace-pre-line">{{ $application->notes }}</p>
                    </div>
                </div>
            @endif

        </div>

        <!-- Timestamps Footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 text-sm text-gray-500">
            <div class="flex justify-between">
                <span>Created: {{ $application->created_at->format('M d, Y g:i A') }}</span>
                <span>Last Updated: {{ $application->updated_at->format('M d, Y g:i A') }}</span>
            </div>
        </div>

    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('applications.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
            ← Back to Applications
        </a>
    </div>
</div>
@endsection

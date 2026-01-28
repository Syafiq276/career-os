@extends('layouts.app')

@section('title', 'All Applications')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Job Applications</h2>
    <p class="text-gray-600 mt-1">Track and manage your job search journey</p>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="text-sm text-gray-500 mb-1">Total Applications</div>
        <div class="text-2xl font-bold text-gray-800">{{ auth()->user()->applications()->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="text-sm text-gray-500 mb-1">Active</div>
        <div class="text-2xl font-bold text-blue-600">{{ auth()->user()->applications()->whereIn('status', ['applied', 'screening', 'interview'])->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="text-sm text-gray-500 mb-1">Offers</div>
        <div class="text-2xl font-bold text-green-600">{{ auth()->user()->applications()->where('status', 'offer')->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="text-sm text-gray-500 mb-1">Rejected</div>
        <div class="text-2xl font-bold text-red-600">{{ auth()->user()->applications()->where('status', 'rejected')->count() }}</div>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('applications.index') }}" class="flex flex-wrap gap-4">
        
        <!-- Search Box -->
        <div class="flex-1 min-w-[200px]">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="🔍 Search company or job title..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
        </div>

        <!-- Status Filter -->
        <div class="min-w-[180px]">
            <select name="status" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buttons -->
        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
            Filter
        </button>
        <a href="{{ route('applications.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
            Clear
        </a>
    </form>
</div>

<!-- Applications Table -->
@if($applications->count() > 0)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($applications as $application)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $application->company_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $application->job_title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $application->location ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $application->applied_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('applications.show', $application) }}" 
                                   class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="{{ route('applications.edit', $application) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form method="POST" 
                                      action="{{ route('applications.destroy', $application) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this application?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $applications->links() }}
    </div>

@else
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <p class="text-gray-500 text-lg mb-4">No applications found</p>
        <a href="{{ route('applications.create') }}" 
           class="inline-block bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
            ➕ Add Your First Application
        </a>
    </div>
@endif

@endsection

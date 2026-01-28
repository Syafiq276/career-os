@extends('layouts.app')

@section('title', 'Edit Application')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Edit Application</h2>
        <p class="text-gray-600 mt-1">Update {{ $application->company_name }} - {{ $application->job_title }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-8">
        <form method="POST" action="{{ route('applications.update', $application) }}">
            @csrf
            @method('PUT')

            <!-- Company Name -->
            <div class="mb-6">
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Company Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="company_name" 
                       name="company_name" 
                       value="{{ old('company_name', $application->company_name) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('company_name') border-red-500 @enderror">
                @error('company_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Job Title -->
            <div class="mb-6">
                <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">
                    Job Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="job_title" 
                       name="job_title" 
                       value="{{ old('job_title', $application->job_title) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('job_title') border-red-500 @enderror">
                @error('job_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Job Link -->
            <div class="mb-6">
                <label for="job_link" class="block text-sm font-medium text-gray-700 mb-2">
                    Job Posting URL
                </label>
                <input type="url" 
                       id="job_link" 
                       name="job_link" 
                       value="{{ old('job_link', $application->job_link) }}"
                       placeholder="https://example.com/careers/job-id"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('job_link') border-red-500 @enderror">
                @error('job_link')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location & Salary Range (Row) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', $application->location) }}"
                           placeholder="New York, NY or Remote"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('location') border-red-500 @enderror">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="salary_range" class="block text-sm font-medium text-gray-700 mb-2">
                        Salary Range
                    </label>
                    <input type="text" 
                           id="salary_range" 
                           name="salary_range" 
                           value="{{ old('salary_range', $application->salary_range) }}"
                           placeholder="$60,000 - $80,000"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('salary_range') border-red-500 @enderror">
                    @error('salary_range')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status & Applied Date (Row) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('status') border-red-500 @enderror">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $application->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="applied_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Applied Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="applied_at" 
                           name="applied_at" 
                           value="{{ old('applied_at', $application->applied_at->format('Y-m-d')) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('applied_at') border-red-500 @enderror">
                    @error('applied_at')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Interview Date -->
            <div class="mb-6">
                <label for="interview_at" class="block text-sm font-medium text-gray-700 mb-2">
                    Interview Date (if scheduled)
                </label>
                <input type="date" 
                       id="interview_at" 
                       name="interview_at" 
                       value="{{ old('interview_at', $application->interview_at?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('interview_at') border-red-500 @enderror">
                @error('interview_at')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          placeholder="Additional information, contacts, requirements, etc."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $application->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('applications.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    💾 Update Application
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

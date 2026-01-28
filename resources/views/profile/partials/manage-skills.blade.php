<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Skill Tree Management') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Add and manage your skills for the RPG-style skill tree visualization on your portfolio.") }}
        </p>

        <!-- Auto-generate button -->
        <div class="mt-4">
            <form method="POST" action="{{ route('skills.generate') }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Auto-Generate from Projects
                </button>
            </form>
            <p class="mt-2 text-xs text-gray-500">
                Automatically detect skills from your synced GitHub repositories' tech stacks.
            </p>
        </div>

        @if (session('skill-error'))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">{{ session('skill-error') }}</p>
            </div>
        @endif
    </header>

    <!-- Add New Skill Form -->
    <form method="post" action="{{ route('skills.store') }}" class="mt-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="skill_name" :value="__('Skill Name')" />
                <x-text-input id="skill_name" name="name" type="text" class="mt-1 block w-full" placeholder="e.g., PHP" required />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="skill_score" :value="__('Score (0-100)')" />
                <x-text-input id="skill_score" name="score" type="number" min="0" max="100" class="mt-1 block w-full" placeholder="85" required />
                <x-input-error class="mt-2" :messages="$errors->get('score')" />
            </div>

            <div>
                <x-input-label for="skill_category" :value="__('Category')" />
                <select id="skill_category" name="category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Select category...</option>
                    <option value="Frontend">Frontend</option>
                    <option value="Backend">Backend</option>
                    <option value="Database">Database</option>
                    <option value="DevOps">DevOps</option>
                    <option value="Tools">Tools</option>
                    <option value="Other">Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('category')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Add Skill') }}</x-primary-button>

            @if (session('skill-added'))
                <p class="text-sm text-green-600">{{ __('Skill added successfully!') }}</p>
            @endif
        </div>
    </form>

    <!-- Existing Skills List -->
    @if($user->skills_data->count() > 0)
        <div class="mt-8">
            <h3 class="text-md font-medium text-gray-900 mb-4">{{ __('Your Skills') }}</h3>
            
            <div class="space-y-2">
                @foreach($user->skills_data as $skill)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <span class="font-medium text-gray-900">{{ $skill->name }}</span>
                            <span class="text-sm text-gray-500 ml-2">({{ $skill->category }})</span>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <!-- Score Bar -->
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $skill->score }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 w-10 text-right">{{ $skill->score }}</span>
                            </div>

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('skills.destroy', $skill) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure you want to delete this skill?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>No skills added yet.</strong> Add your skills above to populate your skill tree visualization on your portfolio.
            </p>
        </div>
    @endif
</section>

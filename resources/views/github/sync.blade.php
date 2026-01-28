<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Sync GitHub Projects') }}
            </h2>
            <form action="{{ route('github.sync-all') }}" method="POST">
                @csrf
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">
                    Sync All Repos
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Pinned Repositories -->
            @if(count($pinnedRepos) > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">📌 Pinned Repositories</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pinnedRepos as $repo)
                                <div class="border border-emerald-500 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                                    <h4 class="font-bold text-emerald-400 mb-2">{{ $repo['name'] }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        {{ $repo['description'] ?? 'No description' }}
                                    </p>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($repo['languages']['nodes'] ?? [] as $lang)
                                            <span class="px-2 py-1 bg-slate-700 text-emerald-400 text-xs rounded">
                                                {{ $lang['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span>⭐ {{ $repo['stargazerCount'] }}</span>
                                        <span>🔀 {{ $repo['forkCount'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- All Repositories -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Select Repositories to Sync</h3>
                    
                    <form action="{{ route('github.sync') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-3 max-h-96 overflow-y-auto mb-4">
                            @forelse($repos as $repo)
                                @php
                                    $isSynced = in_array($repo['name'], $syncedRepos);
                                @endphp
                                
                                <div class="flex items-start gap-3 border-b border-gray-700 pb-3">
                                    <input 
                                        type="checkbox" 
                                        name="repos[]" 
                                        value="{{ $repo['name'] }}"
                                        id="repo-{{ $repo['id'] }}"
                                        {{ $isSynced ? 'disabled checked' : '' }}
                                        class="mt-1"
                                    >
                                    
                                    <label for="repo-{{ $repo['id'] }}" class="flex-1 cursor-pointer">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-emerald-400">
                                                    {{ $repo['name'] }}
                                                    @if($isSynced)
                                                        <span class="text-xs text-green-500 ml-2">✓ Synced</span>
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ $repo['description'] ?? 'No description available' }}
                                                </p>
                                                <div class="flex gap-3 mt-2 text-xs text-gray-500">
                                                    @if($repo['language'])
                                                        <span>🔵 {{ $repo['language'] }}</span>
                                                    @endif
                                                    <span>⭐ {{ $repo['stargazers_count'] }}</span>
                                                    <span>🔀 {{ $repo['forks_count'] }}</span>
                                                    <span>Updated: {{ \Carbon\Carbon::parse($repo['updated_at'])->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            
                                            <a href="{{ $repo['html_url'] }}" target="_blank" 
                                               class="text-blue-400 hover:text-blue-300 text-sm ml-4">
                                                View →
                                            </a>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <p class="text-gray-500">No repositories found for this account.</p>
                            @endforelse
                        </div>

                        @if(count($repos) > 0)
                            <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                                <button type="button" onclick="toggleSelectAll()" 
                                        class="text-emerald-400 hover:text-emerald-300">
                                    Select All
                                </button>
                                
                                <button type="submit" 
                                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded font-semibold">
                                    Sync Selected Repositories
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-blue-900 border border-blue-500 rounded-lg p-4">
                <h4 class="font-semibold text-blue-300 mb-2">💡 How it works</h4>
                <ul class="text-sm text-blue-200 space-y-1">
                    <li>• Select repositories you want to showcase as projects</li>
                    <li>• We'll automatically calculate difficulty and XP based on stars, forks, and complexity</li>
                    <li>• Tech stack is extracted from repository languages</li>
                    <li>• Already synced repositories are marked and can't be duplicated</li>
                    <li>• You can manually edit projects after syncing</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('input[name="repos[]"]:not(:disabled)');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }
    </script>
</x-app-layout>

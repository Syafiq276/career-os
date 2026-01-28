<section>
    <header>
        <h2 class="text-lg font-bold text-emerald-400 font-mono flex items-center">
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
            </svg>
            {{ __('GitHub Integration') }}
        </h2>

        <p class="mt-1 text-sm text-gray-300 font-mono">
            {{ __("Connect your GitHub account to automatically sync repositories as completed quests.") }}
        </p>
    </header>

    @if (session('status') === 'github-connected')
        <div class="mt-4 p-3 bg-emerald-900 border border-emerald-500 rounded text-emerald-400 font-mono text-sm">
            ✓ GitHub connected successfully!
        </div>
    @endif

    @if (session('status') === 'github-disconnected')
        <div class="mt-4 p-3 bg-slate-900 border border-gray-500 rounded text-gray-400 font-mono text-sm">
            GitHub account disconnected.
        </div>
    @endif

    @if (session('status') === 'repos-synced')
        <div class="mt-4 p-3 bg-emerald-900 border border-emerald-500 rounded text-emerald-400 font-mono text-sm">
            ✓ {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 p-3 bg-red-900 border border-red-500 rounded text-red-400 font-mono text-sm">
            ✗ {{ session('error') }}
        </div>
    @endif

    <div class="mt-6">
        @if($user->github_token)
            <!-- GitHub Connected -->
            <div class="bg-slate-900 rounded-lg p-4 border border-emerald-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center text-slate-900 font-bold text-xl">
                            {{ strtoupper(substr($user->github_username ?? 'U', 0, 1)) }}
                        </div>
                        <div class="ml-4">
                            <p class="text-white font-mono font-bold">@{{ $user->github_username }}</p>
                            <p class="text-emerald-400 text-xs font-mono">STATUS: CONNECTED</p>
                        </div>
                    </div>
                    <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                </div>

                <div class="space-y-3">
                    <!-- Sync Repositories Button -->
                    <form method="POST" action="{{ route('github.sync') }}">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-slate-900 font-bold font-mono py-3 px-4 rounded transition">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                SYNC_REPOSITORIES()
                            </span>
                        </button>
                    </form>

                    <!-- Disconnect Button -->
                    <form method="POST" action="{{ route('github.disconnect') }}">
                        @csrf
                        <button type="submit" class="w-full bg-slate-700 hover:bg-slate-600 text-gray-300 font-mono py-2 px-4 rounded border border-red-500 hover:border-red-400 transition">
                            DISCONNECT_GITHUB()
                        </button>
                    </form>
                </div>

                <div class="mt-4 p-3 bg-slate-950 rounded border border-slate-700">
                    <p class="text-xs text-gray-400 font-mono">
                        <span class="text-emerald-400">INFO:</span> Syncing will fetch your repositories and convert them to quests. XP is calculated based on stars, forks, and activity.
                    </p>
                </div>
            </div>
        @else
            <!-- GitHub Not Connected -->
            <div class="bg-slate-900 rounded-lg p-6 border border-gray-700 text-center">
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-gray-300 font-mono mb-2">
                    GitHub Not Connected
                </h3>
                
                <p class="text-gray-400 text-sm font-mono mb-6">
                    Connect your GitHub account to automatically import your repositories as completed quests.
                </p>

                <a href="{{ route('github.redirect') }}" class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-slate-900 font-bold font-mono py-3 px-6 rounded transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                    CONNECT_GITHUB()
                </a>

                <div class="mt-6 p-4 bg-slate-950 rounded border border-slate-700 text-left">
                    <p class="text-xs text-gray-400 font-mono mb-2">
                        <span class="text-emerald-400">FEATURES:</span>
                    </p>
                    <ul class="text-xs text-gray-400 font-mono space-y-1">
                        <li>→ Auto-import repositories as quests</li>
                        <li>→ Calculate XP from stars & forks</li>
                        <li>→ Auto-detect tech stack/languages</li>
                        <li>→ Determine difficulty levels</li>
                        <li>→ Keep portfolio always updated</li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</section>

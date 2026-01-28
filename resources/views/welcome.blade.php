<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} | CareerOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto+Mono:wght@300;400;500;700&display=swap');

        :root {
            --accent: 16 185 129;
            --accent-2: 6 182 212;
            --accent-3: 139 92 246;
            --accent-warm: 245 158 11;
        }
        
        body {
            font-family: 'Roboto Mono', monospace;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }
        
        .neon-border {
            box-shadow: 0 0 20px rgba(var(--accent) / 0.3), inset 0 0 20px rgba(var(--accent) / 0.1);
            border: 2px solid rgba(var(--accent) / 0.5);
        }

        .neon-text {
            text-shadow: 0 0 10px rgba(var(--accent) / 0.8), 0 0 20px rgba(var(--accent) / 0.5);
        }
        
        .glitch {
            animation: glitch 3s infinite;
        }
        
        @keyframes glitch {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
        }
        
        .pulse-glow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .terminal-line::before {
            content: '> ';
            color: rgb(var(--accent));
        }

        .text-emerald-400 { color: rgb(var(--accent)) !important; }
        .text-emerald-500 { color: rgb(var(--accent)) !important; }
        .border-emerald-500 { border-color: rgb(var(--accent)) !important; }
        .bg-emerald-500 { background-color: rgb(var(--accent)) !important; }
        .from-emerald-500 { --tw-gradient-from: rgb(var(--accent)) var(--tw-gradient-from-position) !important; }

        .text-cyan-400 { color: rgb(var(--accent-2)) !important; }
        .border-cyan-500 { border-color: rgb(var(--accent-2)) !important; }
        .to-cyan-400 { --tw-gradient-to: rgb(var(--accent-2)) var(--tw-gradient-to-position) !important; }

        .text-purple-400 { color: rgb(var(--accent-3)) !important; }
        .border-purple-500 { border-color: rgb(var(--accent-3)) !important; }

        .text-yellow-400 { color: rgb(var(--accent-warm)) !important; }
        .border-yellow-500 { border-color: rgb(var(--accent-warm)) !important; }
    </style>
</head>
<body class="bg-slate-900 text-gray-100 min-h-screen">

    <!-- Navigation Bar -->
    <nav class="bg-slate-950 border-b-2 border-emerald-500 sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center">
                    <span class="text-lg sm:text-2xl font-orbitron font-bold text-emerald-400 neon-text glitch">
                        &lt;CAREER<span class="text-yellow-400">OS</span>/&gt;
                    </span>
                    <span class="ml-2 sm:ml-4 text-[10px] sm:text-xs text-emerald-500 font-mono">v2.0.26</span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-6">
                    <div class="hidden sm:flex items-center gap-2">
                        <span class="text-[10px] sm:text-xs text-gray-400 font-mono">THEME</span>
                        <select id="industryTheme" class="bg-slate-800 border border-slate-600 text-gray-200 text-[10px] sm:text-xs font-mono px-2 py-1 rounded">
                            <option value="it">IT / Software</option>
                            <option value="finance">Finance</option>
                            <option value="engineering">Engineering</option>
                            <option value="design">Design</option>
                            <option value="health">Healthcare</option>
                        </select>
                    </div>
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('portfolio.show', ['id' => auth()->id()]) }}" class="text-emerald-400 hover:text-emerald-300 transition font-mono text-[10px] sm:text-sm">
                                <span class="hidden sm:inline">[ MY_PORTFOLIO ]</span>
                                <span class="sm:hidden">[ MINE ]</span>
                            </a>
                            <a href="{{ route('applications.index') }}" class="text-cyan-400 hover:text-cyan-300 transition font-mono text-[10px] sm:text-sm">
                                <span class="hidden sm:inline">[ ADMIN_PANEL ]</span>
                                <span class="sm:hidden">[ ADMIN ]</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 transition font-mono text-[10px] sm:text-sm">
                            [ LOGIN ]
                        </a>
                        <a href="{{ route('register') }}" class="bg-emerald-500 hover:brightness-110 px-2 py-1 sm:px-4 sm:py-2 text-slate-900 font-bold font-mono text-[10px] sm:text-sm transition">
                            <span class="hidden sm:inline">[ REGISTER ]</span>
                            <span class="sm:hidden">[ REG ]</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-12">

        <!-- HUD Header Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            
            <!-- Player Avatar & Info -->
            <div class="lg:col-span-1 bg-slate-800 neon-border rounded-lg p-4 sm:p-6">
                <div class="flex flex-col items-center">
                    <!-- Avatar with Progress Ring -->
                    <div class="relative mb-3 sm:mb-4">
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-emerald-500 via-cyan-500 to-purple-600 flex items-center justify-center text-3xl sm:text-4xl font-orbitron font-bold text-slate-900 shadow-lg pulse-glow">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-emerald-500 px-3 py-1 rounded-full text-slate-900 font-orbitron font-bold text-xs">
                            LVL {{ $level }}
                        </div>
                    </div>
                    
                    <h1 class="text-xl sm:text-2xl font-orbitron font-bold text-emerald-400 mb-1 neon-text text-center">
                        {{ strtoupper($user->name) }}
                    </h1>
                    
                    @if($user->job_title)
                        <p class="text-yellow-400 font-mono text-xs sm:text-sm mb-2 text-center">[ {{ strtoupper($user->job_title) }} ]</p>
                    @else
                        <p class="text-yellow-400 font-mono text-xs sm:text-sm mb-2 text-center">[ BACKEND MAGE ]</p>
                    @endif
                    
                    @if($user->location)
                        <p class="text-gray-500 font-mono text-xs mb-3">📍 {{ $user->location }}</p>
                    @endif
                    
                    @if($user->tagline)
                        <p class="text-cyan-400 text-sm text-center mb-3 italic font-semibold">"{{ $user->tagline }}"</p>
                    @endif
                    
                    @if($user->bio)
                        <p class="text-gray-400 text-sm text-center mb-4 italic">{{ $user->bio }}</p>
                    @endif
                    
                    @if($user->available_for_hire)
                        <div class="mb-4">
                            <span class="px-3 py-1 bg-green-500 text-slate-900 font-orbitron font-bold text-xs rounded-full animate-pulse">
                                🟢 AVAILABLE FOR HIRE
                            </span>
                        </div>
                    @endif

                    <!-- XP Bar -->
                    <div class="w-full mb-2">
                        <div class="flex justify-between text-xs font-mono mb-1">
                            <span class="text-emerald-400">XP</span>
                            <span class="text-gray-400">{{ $totalXp % 1000 }}/1000</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-3 border border-emerald-500">
                            <div class="bg-gradient-to-r from-emerald-500 to-cyan-400 h-full rounded-full transition-all duration-500" style="width: {{ $xpProgress }}%;"></div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 font-mono">{{ $xpToNextLevel }} XP to Level {{ $level + 1 }}</p>
                </div>

                <!-- Social Links -->
                <div class="mt-6 space-y-2">
                    @if($user->resume_path)
                        <a href="{{ Storage::url($user->resume_path) }}" target="_blank" download class="block w-full bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500 border border-yellow-500 px-4 py-2 text-center text-white font-orbitron font-bold text-sm transition shadow-lg">
                            📄 DOWNLOAD_RESUME()
                        </a>
                    @endif
                    
                    @if($user->github_username)
                        <a href="https://github.com/{{ $user->github_username }}" target="_blank" class="block w-full bg-slate-700 hover:bg-slate-600 border border-emerald-500 hover:border-emerald-400 px-4 py-2 text-center text-emerald-400 font-mono text-sm transition">
                            <span class="terminal-line">GITHUB.CONNECT()</span>
                        </a>
                    @endif
                    
                    @if($user->linkedin_url)
                        <a href="{{ $user->linkedin_url }}" target="_blank" class="block w-full bg-slate-700 hover:bg-slate-600 border border-blue-500 hover:border-blue-400 px-4 py-2 text-center text-blue-400 font-mono text-sm transition">
                            <span class="terminal-line">LINKEDIN.CONNECT()</span>
                        </a>
                    @endif
                    
                    @if($user->portfolio_url)
                        <a href="{{ $user->portfolio_url }}" target="_blank" class="block w-full bg-slate-700 hover:bg-slate-600 border border-purple-500 hover:border-purple-400 px-4 py-2 text-center text-purple-400 font-mono text-sm transition">
                            <span class="terminal-line">WEBSITE.OPEN()</span>
                        </a>
                    @endif
                    
                    <!-- Additional Social Media -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 gap-2 mt-4">
                        @if($user->twitter_username)
                            <a href="https://twitter.com/{{ $user->twitter_username }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-sky-500 p-2 text-center text-sky-400 text-xs transition">
                                𝕏 Twitter
                            </a>
                        @endif
                        
                        @if($user->instagram_username)
                            <a href="https://instagram.com/{{ $user->instagram_username }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-pink-500 p-2 text-center text-pink-400 text-xs transition">
                                📷 Instagram
                            </a>
                        @endif
                        
                        @if($user->youtube_url)
                            <a href="{{ $user->youtube_url }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-red-500 p-2 text-center text-red-400 text-xs transition">
                                ▶️ YouTube
                            </a>
                        @endif
                        
                        @if($user->twitch_username)
                            <a href="https://twitch.tv/{{ $user->twitch_username }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-purple-500 p-2 text-center text-purple-400 text-xs transition">
                                🎮 Twitch
                            </a>
                        @endif
                        
                        @if($user->discord_username)
                            <div class="bg-slate-700 border border-indigo-500 p-2 text-center text-indigo-400 text-xs cursor-pointer" onclick="copyDiscord('{{ $user->discord_username }}')">
                                💬 Discord
                            </div>
                        @endif
                        
                        @if($user->stackoverflow_id)
                            <a href="https://stackoverflow.com/users/{{ $user->stackoverflow_id }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-orange-500 p-2 text-center text-orange-400 text-xs transition">
                                📚 Stack
                            </a>
                        @endif
                        
                        @if($user->devto_username)
                            <a href="https://dev.to/{{ $user->devto_username }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-gray-400 p-2 text-center text-gray-300 text-xs transition">
                                📝 DEV
                            </a>
                        @endif
                        
                        @if($user->medium_username)
                            <a href="https://medium.com/@{{ $user->medium_username }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-gray-400 p-2 text-center text-gray-300 text-xs transition">
                                📖 Medium
                            </a>
                        @endif
                        
                        @if($user->behance_username)
                            <a href="https://behance.net/{{ $user->behance_username }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-blue-600 p-2 text-center text-blue-400 text-xs transition">
                                🎨 Behance
                            </a>
                        @endif
                        
                        @if($user->dribbble_username)
                            <a href="https://dribbble.com/{{ $user->dribbble_username }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 border border-pink-600 p-2 text-center text-pink-400 text-xs transition">
                                🏀 Dribbble
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Panel -->
            <div class="lg:col-span-1 bg-slate-800 neon-border rounded-lg p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-orbitron font-bold text-emerald-400 mb-3 sm:mb-4 border-b border-emerald-500 pb-2">
                    [ PLAYER STATS ]
                </h2>
                
                <div class="space-y-4">
                    <!-- Level -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-mono text-sm">LEVEL:</span>
                        <span class="text-emerald-400 font-orbitron font-bold text-xl">{{ $level }}</span>
                    </div>
                    
                    <!-- Total XP -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-mono text-sm">TOTAL_XP:</span>
                        <span class="text-cyan-400 font-orbitron font-bold text-xl">{{ number_format($totalXp) }}</span>
                    </div>
                    
                    <!-- Quests Completed -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-mono text-sm">QUESTS:</span>
                        <span class="text-purple-400 font-orbitron font-bold text-xl">{{ $user->projects->count() }}</span>
                    </div>
                    
                    <!-- Skills Unlocked -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-mono text-sm">SKILLS:</span>
                        <span class="text-yellow-400 font-orbitron font-bold text-xl">{{ $skills->count() }}</span>
                    </div>

                    <!-- Health Bar (Caffeine) -->
                    <div class="pt-4">
                        <div class="flex justify-between text-xs font-mono mb-1">
                            <span class="text-red-400">CAFFEINE:</span>
                            <span class="text-gray-400">100/100</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-3 border border-red-500">
                            <div class="bg-gradient-to-r from-red-500 to-orange-400 h-full rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skill Radar Chart -->
            <div class="lg:col-span-1 bg-slate-800 neon-border rounded-lg p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-orbitron font-bold text-emerald-400 mb-3 sm:mb-4 border-b border-emerald-500 pb-2">
                    [ SKILL TREE ]
                </h2>
                <canvas id="skillRadar" class="max-w-full h-auto"></canvas>
            </div>
        </div>

        <!-- Quest Board (Projects) -->
        <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-orbitron font-bold text-emerald-400 mb-4 sm:mb-6 neon-text">
                [ COMPLETED QUESTS ]
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($featuredQuests as $quest)
                    <div class="bg-slate-800 border-2 {{ $quest->difficulty === 'Legendary' ? 'border-yellow-500' : ($quest->difficulty === 'Hardcore' ? 'border-purple-500' : ($quest->difficulty === 'Normal' ? 'border-blue-500' : 'border-green-500')) }} rounded-lg p-6 hover:transform hover:scale-105 transition duration-300 group">
                        <!-- Difficulty Badge -->
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-3 py-1 rounded text-xs font-orbitron font-bold {{ $quest->difficulty === 'Legendary' ? 'bg-yellow-500 text-slate-900' : ($quest->difficulty === 'Hardcore' ? 'bg-purple-500 text-white' : ($quest->difficulty === 'Normal' ? 'bg-blue-500 text-white' : 'bg-green-500 text-white')) }}">
                                {{ strtoupper($quest->difficulty) }}
                            </span>
                            <span class="text-emerald-400 font-orbitron font-bold text-sm">+{{ $quest->xp_gained }} XP</span>
                        </div>

                        <!-- Quest Title -->
                        <h3 class="text-xl font-orbitron font-bold text-gray-100 mb-2 group-hover:text-emerald-400 transition">
                            {{ $quest->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-400 text-sm mb-4">
                            {{ $quest->description }}
                        </p>

                        <!-- Tech Stack (Loot) -->
                        @if($quest->tech_stack && count($quest->tech_stack) > 0)
           Discord username copy function
        function copyDiscord(username) {
            navigator.clipboard.writeText(username);
            alert('Discord username copied: ' + username);
        }
        
        //                  <div class="mb-4">
                                <p class="text-xs font-mono text-gray-500 mb-2">[ LOOT_GAINED ]:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($quest->tech_stack as $tech)
                                        <span class="px-2 py-1 bg-slate-700 border border-emerald-500 rounded text-xs text-emerald-400 font-mono">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        @if($quest->repo_link)
                            <a href="{{ $quest->repo_link }}" target="_blank" class="block w-full bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-orbitron font-bold text-center py-2 rounded transition">
                                [ INSPECT CODE ]
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12">
                        <p class="text-gray-500 font-mono">No quests completed yet. The adventure begins...</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- About Section -->
        <div class="bg-gradient-to-r from-emerald-900 to-cyan-900 neon-border rounded-lg p-8 text-center">
            <h2 class="text-3xl font-orbitron font-bold text-emerald-400 mb-4 neon-text">
                [ ABOUT CAREER<span class="text-yellow-400">OS</span> ]
            </h2>
            <p class="text-gray-300 text-lg max-w-3xl mx-auto font-mono leading-relaxed">
                A <span class="text-emerald-400 font-bold">gamified portfolio system</span> that transforms your career journey into an RPG adventure. 
                Track your projects as <span class="text-purple-400 font-bold">quests</span>, 
                level up your <span class="text-cyan-400 font-bold">skills</span>, 
                and showcase your progression to potential employers in a unique, 
                <span class="text-yellow-400 font-bold">cyberpunk-inspired</span> interface.
            </p>
            <div class="mt-6 flex justify-center gap-4 flex-wrap">
                <span class="px-4 py-2 bg-slate-800 border border-emerald-500 rounded text-emerald-400 font-mono text-sm">
                    Laravel 11
                </span>
                <span class="px-4 py-2 bg-slate-800 border border-cyan-500 rounded text-cyan-400 font-mono text-sm">
                    TailwindCSS
                </span>
                <span class="px-4 py-2 bg-slate-800 border border-purple-500 rounded text-purple-400 font-mono text-sm">
                    Chart.js
                </span>
                <span class="px-4 py-2 bg-slate-800 border border-yellow-500 rounded text-yellow-400 font-mono text-sm">
                    PHP 8.2+
                </span>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t-2 border-emerald-500 mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500 font-mono text-sm">
                &copy; {{ date('Y') }} CareerOS. Built with <span class="text-red-500">❤</span> and <span class="text-emerald-400">{ code }</span>
            </p>
            <p class="text-gray-600 font-mono text-xs mt-2">
                system.status = <span class="text-emerald-400">OPERATIONAL</span>
            </p>
        </div>
    </footer>

    <!-- Chart.js Configuration -->
    <script>
        const skillData = @json($skills);

        const getRgb = (varName) => {
            const value = getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
            const [r, g, b] = value.split(' ').map((v) => parseInt(v, 10));
            return { r, g, b };
        };

        const rgba = ({ r, g, b }, alpha) => `rgba(${r}, ${g}, ${b}, ${alpha})`;

        const themePresets = {
            it: {
                accent: [16, 185, 129],
                accent2: [6, 182, 212],
                accent3: [139, 92, 246],
                warm: [245, 158, 11]
            },
            finance: {
                accent: [37, 99, 235],
                accent2: [34, 197, 94],
                accent3: [14, 116, 144],
                warm: [234, 179, 8]
            },
            engineering: {
                accent: [249, 115, 22],
                accent2: [148, 163, 184],
                accent3: [234, 179, 8],
                warm: [245, 158, 11]
            },
            design: {
                accent: [236, 72, 153],
                accent2: [168, 85, 247],
                accent3: [56, 189, 248],
                warm: [251, 146, 60]
            },
            health: {
                accent: [20, 184, 166],
                accent2: [34, 197, 94],
                accent3: [59, 130, 246],
                warm: [248, 113, 113]
            }
        };

        const applyTheme = (key) => {
            const theme = themePresets[key] || themePresets.it;
            const root = document.documentElement;
            root.style.setProperty('--accent', theme.accent.join(' '));
            root.style.setProperty('--accent-2', theme.accent2.join(' '));
            root.style.setProperty('--accent-3', theme.accent3.join(' '));
            root.style.setProperty('--accent-warm', theme.warm.join(' '));
            localStorage.setItem('portfolioTheme', key);

            if (window.skillRadar) {
                const accent = getRgb('--accent');
                window.skillRadar.data.datasets[0].backgroundColor = rgba(accent, 0.2);
                window.skillRadar.data.datasets[0].borderColor = rgba(accent, 1);
                window.skillRadar.data.datasets[0].pointBackgroundColor = rgba(accent, 1);
                window.skillRadar.data.datasets[0].pointHoverBorderColor = rgba(accent, 1);
                window.skillRadar.options.scales.r.grid.color = rgba(accent, 0.2);
                window.skillRadar.options.scales.r.pointLabels.color = rgba(accent, 1);
                window.skillRadar.options.scales.r.angleLines.color = rgba(accent, 0.1);
                window.skillRadar.options.plugins.tooltip.titleColor = rgba(accent, 1);
                window.skillRadar.options.plugins.tooltip.borderColor = rgba(accent, 1);
                window.skillRadar.update();
            }
        };

        const ctx = document.getElementById('skillRadar').getContext('2d');
        const accent = getRgb('--accent');

        window.skillRadar = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: skillData.map(s => s.name),
                datasets: [{
                    label: 'Skill Level',
                    data: skillData.map(s => s.score),
                    backgroundColor: rgba(accent, 0.2),
                    borderColor: rgba(accent, 1),
                    borderWidth: 2,
                    pointBackgroundColor: rgba(accent, 1),
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: rgba(accent, 1),
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            color: '#6b7280',
                            backdropColor: 'transparent',
                            font: {
                                family: "'Roboto Mono', monospace",
                                size: 10
                            }
                        },
                        grid: {
                            color: rgba(accent, 0.2),
                            lineWidth: 1
                        },
                        pointLabels: {
                            color: rgba(accent, 1),
                            font: {
                                family: "'Roboto Mono', monospace",
                                size: 11,
                                weight: 'bold'
                            }
                        },
                        angleLines: {
                            color: rgba(accent, 0.1)
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: rgba(accent, 1),
                        bodyColor: '#d1d5db',
                        borderColor: rgba(accent, 1),
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        titleFont: {
                            family: "'Orbitron', sans-serif",
                            size: 12,
                            weight: 'bold'
                        },
                        bodyFont: {
                            family: "'Roboto Mono', monospace",
                            size: 11
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Level: ' + context.parsed.r + '/100';
                            }
                        }
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('industryTheme');
            const saved = localStorage.getItem('portfolioTheme') || 'it';
            if (select) {
                select.value = saved;
                select.addEventListener('change', (event) => applyTheme(event.target.value));
            }
            applyTheme(saved);
        });
    </script>

</body>
</html>

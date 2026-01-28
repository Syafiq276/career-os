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
        
        body {
            font-family: 'Roboto Mono', monospace;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }
        
        .neon-border {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3), inset 0 0 20px rgba(16, 185, 129, 0.1);
            border: 2px solid rgba(16, 185, 129, 0.5);
        }
        
        .neon-text {
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.8), 0 0 20px rgba(16, 185, 129, 0.5);
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
            color: #10b981;
        }
    </style>
</head>
<body class="bg-slate-900 text-gray-100 min-h-screen">

    <!-- Navigation Bar -->
    <nav class="bg-slate-950 border-b-2 border-emerald-500 sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-orbitron font-bold text-emerald-400 neon-text glitch">
                        &lt;CAREER<span class="text-yellow-400">OS</span>/&gt;
                    </span>
                    <span class="ml-4 text-xs text-emerald-500 font-mono">v2.0.26</span>
                </div>
                <div class="flex items-center space-x-6">
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('applications.index') }}" class="text-emerald-400 hover:text-emerald-300 transition font-mono text-sm">
                                [ ADMIN_PANEL ]
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 transition font-mono text-sm">
                            [ LOGIN ]
                        </a>
                        <a href="{{ route('register') }}" class="bg-emerald-500 hover:bg-emerald-600 px-4 py-2 text-slate-900 font-bold font-mono text-sm transition">
                            [ REGISTER ]
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- HUD Header Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Player Avatar & Info -->
            <div class="lg:col-span-1 bg-slate-800 neon-border rounded-lg p-6">
                <div class="flex flex-col items-center">
                    <!-- Avatar with Progress Ring -->
                    <div class="relative mb-4">
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-emerald-500 via-cyan-500 to-purple-600 flex items-center justify-center text-4xl font-orbitron font-bold text-slate-900 shadow-lg pulse-glow">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-emerald-500 px-3 py-1 rounded-full text-slate-900 font-orbitron font-bold text-xs">
                            LVL {{ $level }}
                        </div>
                    </div>
                    
                    <h1 class="text-2xl font-orbitron font-bold text-emerald-400 mb-1 neon-text">
                        {{ strtoupper($user->name) }}
                    </h1>
                    <p class="text-yellow-400 font-mono text-sm mb-3">[ BACKEND MAGE ]</p>
                    
                    @if($user->bio)
                        <p class="text-gray-400 text-sm text-center mb-4 italic">{{ $user->bio }}</p>
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
                </div>
            </div>

            <!-- Stats Panel -->
            <div class="lg:col-span-1 bg-slate-800 neon-border rounded-lg p-6">
                <h2 class="text-lg font-orbitron font-bold text-emerald-400 mb-4 border-b border-emerald-500 pb-2">
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
            <div class="lg:col-span-1 bg-slate-800 neon-border rounded-lg p-6">
                <h2 class="text-lg font-orbitron font-bold text-emerald-400 mb-4 border-b border-emerald-500 pb-2">
                    [ SKILL TREE ]
                </h2>
                <canvas id="skillRadar" width="300" height="300"></canvas>
            </div>
        </div>

        <!-- Quest Board (Projects) -->
        <div class="mb-8">
            <h2 class="text-3xl font-orbitron font-bold text-emerald-400 mb-6 neon-text">
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
                            <div class="mb-4">
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
        // Skill Radar Chart
        const skillData = {!! json_encode($skills) !!};
        
        const ctx = document.getElementById('skillRadar').getContext('2d');
        const skillRadar = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: skillData.map(s => s.name),
                datasets: [{
                    label: 'Skill Level',
                    data: skillData.map(s => s.score),
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(16, 185, 129, 1)',
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
                            color: 'rgba(16, 185, 129, 0.2)',
                            lineWidth: 1
                        },
                        pointLabels: {
                            color: '#10b981',
                            font: {
                                family: "'Roboto Mono', monospace",
                                size: 11,
                                weight: 'bold'
                            }
                        },
                        angleLines: {
                            color: 'rgba(16, 185, 129, 0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#10b981',
                        bodyColor: '#d1d5db',
                        borderColor: '#10b981',
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
    </script>

</body>
</html>

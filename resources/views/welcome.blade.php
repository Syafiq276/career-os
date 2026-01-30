<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} | CareerOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f8fafc; color: #0f172a; }
        .neon-border { border: 1px solid #e2e8f0; box-shadow: none; }
        .neon-text { text-shadow: none; }
        .glitch { animation: none; }
        .pulse-glow { animation: none; }

        .text-emerald-400, .text-emerald-500 { color: #334155 !important; }
        .text-cyan-400 { color: #475569 !important; }
        .text-purple-400 { color: #475569 !important; }
        .text-yellow-400 { color: #475569 !important; }
        .border-emerald-100, .border-emerald-200, .border-emerald-500 { border-color: #e2e8f0 !important; }
        .bg-emerald-500 { background-color: #334155 !important; color: #ffffff !important; }
        .bg-emerald-50 { background-color: #f1f5f9 !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        <!-- Side Navigation -->
        <aside class="hidden md:flex w-64 flex-col border-r border-slate-200 bg-white px-6 py-8">
            <div class="mb-8">
                <div class="text-lg font-semibold text-slate-800">CareerOS</div>
                <div class="text-xs text-slate-400 font-mono">v2.0.26</div>
            </div>
            <nav class="flex flex-col gap-3 text-sm">
                <a href="#home" class="text-slate-600 hover:text-slate-900">Home</a>
                <a href="#projects" class="text-slate-600 hover:text-slate-900">Projects</a>
                <a href="#skills" class="text-slate-600 hover:text-slate-900">Skills</a>
                <a href="#about" class="text-slate-600 hover:text-slate-900">About</a>
                <a href="#contact" class="text-slate-600 hover:text-slate-900">Contact</a>
            </nav>
            <div class="mt-8 border-t border-slate-200 pt-4 text-sm">
                @auth
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('portfolio.show', ['id' => auth()->id()]) }}" class="block text-slate-600 hover:text-slate-900">My Portfolio</a>
                        <a href="{{ route('applications.index') }}" class="block text-slate-600 hover:text-slate-900 mt-2">Admin Panel</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block text-slate-600 hover:text-slate-900">Login</a>
                    <a href="{{ route('register') }}" class="block text-slate-600 hover:text-slate-900 mt-2">Register</a>
                @endauth
            </div>
        </aside>

        <div class="flex-1">
            <!-- Main Content -->
            <main id="home" class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-12">
        @php
            $skillsByCategory = $skills->groupBy('category');
        @endphp

        <!-- Hero -->
        <section class="bg-white border border-emerald-100 rounded-2xl p-6 sm:p-10 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="text-xs font-mono text-emerald-500">Portfolio</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">
                        Welcome to My <span class="text-emerald-500">{{ $user->job_title ? $user->job_title : 'IT Journey' }}</span>
                    </h1>
                    <p class="text-gray-600 mt-4">
                        {{ $user->bio ?? 'Building modern web experiences and solving problems through software engineering, mobile apps, and data-driven solutions.' }}
                    </p>
                    <div class="mt-6 flex gap-3">
                        <a href="#projects" class="bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">View Projects</a>
                        <a href="#contact" class="border border-emerald-200 text-emerald-600 px-4 py-2 rounded-lg text-sm font-semibold">Get in Touch</a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="bg-slate-800 neon-border rounded-2xl p-6 w-full max-w-sm text-center">
                        <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-emerald-500 via-cyan-500 to-purple-600 flex items-center justify-center text-2xl font-orbitron font-bold text-slate-900 shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-orbitron font-bold text-emerald-400 mt-4">{{ strtoupper($user->name) }}</h2>
                        <p class="text-yellow-400 text-xs mt-1">[ {{ strtoupper($user->job_title ?? 'BACKEND MAGE') }} ]</p>
                        <p class="text-gray-400 text-xs mt-2">{{ $user->location ?? 'Open to Remote' }}</p>
                        <div class="mt-4 text-left">
                            <div class="flex justify-between text-xs font-mono mb-1">
                                <span class="text-emerald-400">XP</span>
                                <span class="text-gray-400">{{ $totalXp % 1000 }}/1000</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2 border border-emerald-500">
                                <div class="bg-gradient-to-r from-emerald-500 to-cyan-400 h-full rounded-full" style="width: {{ $xpProgress }}%;"></div>
                            </div>
                            <p class="text-xs text-gray-500 font-mono mt-2">{{ $xpToNextLevel }} XP to Level {{ $level + 1 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What I Do -->
        <section class="mt-10" id="what-i-do">
            <h2 class="text-xl font-semibold text-gray-900 text-center">What I Do</h2>
            <p class="text-gray-500 text-sm text-center mt-2">Exploring technology through web, mobile, and data solutions.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-900">Web Development</h3>
                    <p class="text-gray-500 text-sm mt-2">Building responsive websites and web apps with modern stacks.</p>
                </div>
                <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-900">Mobile Applications</h3>
                    <p class="text-gray-500 text-sm mt-2">Designing mobile-first apps and seamless user experiences.</p>
                </div>
                <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-900">Data & Analytics</h3>
                    <p class="text-gray-500 text-sm mt-2">Turning data into insights through dashboards and analysis.</p>
                </div>
            </div>
        </section>

        <!-- Projects -->
        <section class="mt-12" id="projects">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900">My Projects</h2>
                <p class="text-gray-500 text-sm mt-2">A showcase of work across web, mobile, and analytics.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @forelse($featuredQuests as $quest)
                    <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-emerald-600">{{ strtoupper($quest->difficulty) }}</span>
                            <span class="text-xs text-gray-400">+{{ $quest->xp_gained }} XP</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mt-2">{{ $quest->title }}</h3>
                        <p class="text-sm text-gray-500 mt-2">{{ $quest->description }}</p>

                        @if($quest->tech_stack && count($quest->tech_stack) > 0)
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach($quest->tech_stack as $tech)
                                    <span class="px-2 py-1 bg-emerald-50 border border-emerald-100 rounded text-xs text-emerald-600">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        @if($quest->repo_link)
                            <a href="{{ $quest->repo_link }}" target="_blank" class="inline-block mt-4 text-emerald-600 text-sm font-semibold">View Code →</a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-gray-400">No projects yet.</div>
                @endforelse
            </div>
        </section>

        <!-- Skills -->
        <section class="mt-12" id="skills">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900">My Skills</h2>
                <p class="text-gray-500 text-sm mt-2">A snapshot of my technical capabilities.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm lg:col-span-1">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Skill Radar</h3>
                    <canvas id="skillRadar" class="max-w-full h-auto"></canvas>
                </div>
                <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($skillsByCategory as $category => $items)
                            <div>
                                <h4 class="text-sm font-semibold text-emerald-600">{{ $category }}</h4>
                                <div class="mt-3 space-y-3">
                                    @foreach($items as $skill)
                                        <div>
                                            <div class="flex justify-between text-xs text-gray-500">
                                                <span>{{ $skill->name }}</span>
                                                <span>{{ $skill->score }}%</span>
                                            </div>
                                            <div class="w-full h-2 bg-emerald-100 rounded-full mt-1">
                                                <div class="h-2 bg-emerald-500 rounded-full" style="width: {{ $skill->score }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section class="mt-12" id="about">
            <div class="bg-white border border-emerald-100 rounded-2xl p-8 text-center shadow-sm">
                <h2 class="text-2xl font-semibold text-gray-900">About Me</h2>
                <p class="text-gray-500 text-sm mt-2 max-w-3xl mx-auto">
                    {{ $user->bio ?? 'A developer focused on building clean, scalable systems and delightful user experiences.' }}
                </p>
                <div class="mt-6 flex justify-center gap-3 flex-wrap">
                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded text-emerald-600 text-xs">Laravel 11</span>
                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded text-emerald-600 text-xs">TailwindCSS</span>
                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded text-emerald-600 text-xs">Chart.js</span>
                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded text-emerald-600 text-xs">PHP 8.2+</span>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="mt-12" id="contact">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">Contact Details</h3>
                    <div class="space-y-3 text-sm text-gray-600 mt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">NAME:</span>
                            <span class="text-emerald-600">{{ strtoupper($user->name) }}</span>
                        </div>
                        @if($user->job_title)
                            <div class="flex justify-between">
                                <span class="text-gray-400">ROLE:</span>
                                <span class="text-gray-700">{{ strtoupper($user->job_title) }}</span>
                            </div>
                        @endif
                        @if($user->location)
                            <div class="flex justify-between">
                                <span class="text-gray-400">LOCATION:</span>
                                <span>{{ $user->location }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-400">EMAIL:</span>
                            <a href="mailto:{{ $user->email }}" class="text-emerald-600 hover:text-emerald-500 transition">{{ $user->email }}</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-emerald-100 rounded-xl p-5 shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900">Send a Message</h3>
                    <form action="mailto:{{ $user->email }}" method="post" enctype="text/plain" class="space-y-4 mt-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input type="text" name="name" placeholder="Your name" class="w-full border border-emerald-100 text-gray-700 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-emerald-300">
                            <input type="email" name="email" placeholder="Your email" class="w-full border border-emerald-100 text-gray-700 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        </div>
                        <input type="text" name="subject" placeholder="Subject" class="w-full border border-emerald-100 text-gray-700 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <textarea name="message" rows="4" placeholder="Message" class="w-full border border-emerald-100 text-gray-700 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-emerald-300"></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-5 py-2 rounded">
                                Send Message
                            </button>
                        </div>
                        <p class="text-xs text-gray-400">Opens your email client to send the message.</p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500 font-mono text-sm">
                &copy; {{ date('Y') }} CareerOS. Built with <span class="text-red-500">❤</span> and <span class="text-slate-600">{ code }</span>
            </p>
            <p class="text-gray-600 font-mono text-xs mt-2">
                system.status = <span class="text-slate-600">OPERATIONAL</span>
            </p>
        </div>
    </footer>

    <!-- Chart.js Configuration -->
    <script>
        const skillData = @json($skills);
        const accent = '100, 116, 139';

        const ctx = document.getElementById('skillRadar').getContext('2d');
        window.skillRadar = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: skillData.map(s => s.name),
                datasets: [{
                    label: 'Skill Level',
                    data: skillData.map(s => s.score),
                    backgroundColor: `rgba(${accent}, 0.15)`,
                    borderColor: `rgba(${accent}, 0.9)`,
                    borderWidth: 2,
                    pointBackgroundColor: `rgba(${accent}, 0.9)`,
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: `rgba(${accent}, 0.9)`,
                    pointRadius: 3,
                    pointHoverRadius: 5
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
                            color: '#94a3b8',
                            backdropColor: 'transparent',
                            font: {
                                family: "'Roboto Mono', monospace",
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.3)',
                            lineWidth: 1
                        },
                        pointLabels: {
                            color: '#475569',
                            font: {
                                family: "'Roboto Mono', monospace",
                                size: 11,
                                weight: 'bold'
                            }
                        },
                        angleLines: {
                            color: 'rgba(148, 163, 184, 0.2)'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5f5',
                        borderColor: 'rgba(100, 116, 139, 0.6)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        titleFont: { family: "'Roboto Mono', monospace", size: 12, weight: 'bold' },
                        bodyFont: { family: "'Roboto Mono', monospace", size: 11 },
                        callbacks: {
                            label: function(context) {
                                return 'Level: ' + context.parsed.r + '/100';
                            }
                        }
                    }
                }
            }
        });

        const copyDiscord = (username) => {
            if (!username) return;
            navigator.clipboard.writeText(username);
            alert('Discord username copied: ' + username);
        };
    </script>
        </div>
    </div>
</body>
</html>

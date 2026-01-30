<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} | CareerOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('portfolio.partials.theme-styles')
</head>
<body class="bg-slate-900 text-gray-100 min-h-screen">

    <!-- Navigation Bar -->
    <nav class="bg-white/90 border-b border-emerald-200 sticky top-0 z-50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center">
                    <span class="text-lg sm:text-2xl font-orbitron font-bold text-emerald-400 neon-text glitch">
                        &lt;CAREER<span class="text-yellow-400">OS</span>/&gt;
                    </span>
                    <span class="ml-2 sm:ml-4 text-[10px] sm:text-xs text-emerald-500 font-mono">v2.0.26</span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-6">
                    <div class="hidden md:flex items-center gap-4 text-xs font-mono">
                        <a href="#home" class="text-gray-500 hover:text-emerald-500 transition">Home</a>
                        <a href="#projects" class="text-gray-500 hover:text-emerald-500 transition">Projects</a>
                        <a href="#skills" class="text-gray-500 hover:text-emerald-500 transition">Skills</a>
                        <a href="#about" class="text-gray-500 hover:text-emerald-500 transition">About</a>
                        <a href="#contact" class="text-gray-500 hover:text-emerald-500 transition">Contact</a>
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <span class="text-[10px] sm:text-xs text-gray-400 font-mono">THEME</span>
                        <select id="industryTheme" class="bg-white border border-emerald-200 text-gray-600 text-[10px] sm:text-xs font-mono px-2 py-1 rounded">
                            <option value="it">IT / Software</option>
                            <option value="vscode">VS Code</option>
                            <option value="finance">Finance</option>
                            <option value="engineering">Engineering</option>
                            <option value="design">Design</option>
                            <option value="health">Healthcare</option>
                        </select>
                    </div>
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('portfolio.show', ['id' => auth()->id()]) }}" class="text-emerald-500 hover:text-emerald-400 transition font-mono text-[10px] sm:text-sm">
                                <span class="hidden sm:inline">[ MY_PORTFOLIO ]</span>
                                <span class="sm:hidden">[ MINE ]</span>
                            </a>
                            <a href="{{ route('applications.index') }}" class="text-cyan-500 hover:text-cyan-400 transition font-mono text-[10px] sm:text-sm">
                                <span class="hidden sm:inline">[ ADMIN_PANEL ]</span>
                                <span class="sm:hidden">[ ADMIN ]</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-emerald-500 hover:text-emerald-400 transition font-mono text-[10px] sm:text-sm">
                            [ LOGIN ]
                        </a>
                        <a href="{{ route('register') }}" class="bg-emerald-500 hover:brightness-110 px-2 py-1 sm:px-4 sm:py-2 text-white font-bold font-mono text-[10px] sm:text-sm transition rounded">
                            <span class="hidden sm:inline">[ REGISTER ]</span>
                            <span class="sm:hidden">[ REG ]</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

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
    <footer class="bg-white border-t border-emerald-100 mt-12 py-6">
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
    @include('portfolio.partials.theme-script')

</body>
</html>

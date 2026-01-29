<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ResuMatch: ATS Diagnostic Tool') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('score') !== null)
            <div class="mb-8 p-6 bg-slate-800 border border-slate-700 rounded-lg shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-500 rounded-full blur-3xl opacity-20"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div class="text-center">
                        <h3 class="text-slate-400 uppercase tracking-widest text-xs font-bold mb-2">Match Score</h3>
                        <div class="relative inline-flex items-center justify-center">
                            <svg class="w-32 h-32 transform -rotate-90">
                                <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="10" fill="transparent" class="text-slate-700" />
                                <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="10" fill="transparent" 
                                    class="{{ session('score') > 70 ? 'text-emerald-500' : (session('score') > 40 ? 'text-yellow-500' : 'text-red-500') }}" 
                                    stroke-dasharray="377" 
                                    stroke-dashoffset="{{ 377 - (377 * session('score') / 100) }}" />
                            </svg>
                            <span class="absolute text-4xl font-black text-white">{{ session('score') }}%</span>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <h3 class="text-slate-400 uppercase tracking-widest text-xs font-bold mb-4">Diagnostic Report</h3>
                        
                        @if(session('score') > 70)
                            <p class="text-emerald-400 font-bold mb-4">System Status: OPTIMIZED</p>
                            <p class="text-slate-300 text-sm">Your resume is well-calibrated for this role.</p>
                        @else
                            <p class="text-red-400 font-bold mb-4">System Status: KEYWORD GAP DETECTED</p>
                            <p class="text-slate-300 text-sm mb-2">The ATS might reject this application. Consider adding these missing keywords:</p>
                            
                            <div class="flex flex-wrap gap-2">
                                @foreach(session('missing') as $keyword)
                                    <span class="px-3 py-1 bg-red-900/50 border border-red-500/30 text-red-200 text-xs rounded-full font-mono">
                                        {{ $keyword }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-slate-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('scanner.analyze') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">1. Upload Target Resume (PDF)</label>
                            <input type="file" name="resume" accept="application/pdf" required
                                class="block w-full text-sm text-slate-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                cursor-pointer bg-slate-900 rounded-lg border border-slate-700 p-2 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">2. Paste Job Description</label>
                            <textarea name="job_description" rows="8" required placeholder="Paste the full job description here..."
                                class="w-full bg-slate-900 border-slate-700 rounded-lg text-slate-300 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm p-4"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg transition-all shadow-lg hover:shadow-blue-500/50">
                                Run Diagnostics
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
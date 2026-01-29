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

    /* Industry layout variations */
    html[data-theme="finance"] body {
        background: linear-gradient(135deg, #0b1120 0%, #111827 100%);
    }
    html[data-theme="finance"] .bg-slate-800 { background-color: #111827 !important; }
    html[data-theme="finance"] .bg-slate-950 { background-color: #0b1120 !important; }
    html[data-theme="finance"] .neon-border {
        box-shadow: 0 0 10px rgba(var(--accent) / 0.15), inset 0 0 10px rgba(var(--accent) / 0.08);
        border-width: 1px;
    }

    html[data-theme="engineering"] body {
        background: linear-gradient(135deg, #0f172a 0%, #2a1f14 100%);
    }
    html[data-theme="engineering"] .bg-slate-800 { background-color: #1e293b !important; }
    html[data-theme="engineering"] .bg-slate-950 { background-color: #0f172a !important; }
    html[data-theme="engineering"] .neon-border {
        box-shadow: 0 0 16px rgba(var(--accent-warm) / 0.25), inset 0 0 16px rgba(var(--accent-warm) / 0.12);
    }

    html[data-theme="design"] body {
        background: radial-gradient(circle at top, #1f103a 0%, #0b1120 55%, #0f172a 100%);
    }
    html[data-theme="design"] .bg-slate-800 { background-color: #1f233a !important; }
    html[data-theme="design"] .bg-slate-950 { background-color: #0b1120 !important; }
    html[data-theme="design"] .neon-border {
        box-shadow: 0 0 24px rgba(var(--accent-3) / 0.35), inset 0 0 20px rgba(var(--accent) / 0.15);
    }

    html[data-theme="health"] body {
        background: linear-gradient(135deg, #0b1120 0%, #0f172a 60%, #0f2f2a 100%);
    }
    html[data-theme="health"] .bg-slate-800 { background-color: #0f1f2b !important; }
    html[data-theme="health"] .bg-slate-950 { background-color: #0b1120 !important; }
    html[data-theme="health"] .neon-border {
        box-shadow: 0 0 18px rgba(var(--accent) / 0.25), inset 0 0 18px rgba(var(--accent-2) / 0.12);
    }
</style>

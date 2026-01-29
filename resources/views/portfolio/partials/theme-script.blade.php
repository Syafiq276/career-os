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
        root.setAttribute('data-theme', key);
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

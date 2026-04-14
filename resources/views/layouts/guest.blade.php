<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Synapse') }} - Acceso</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/favicon.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col relative font-sans">
    <!-- Simple Header -->
    <header class="w-full bg-gradient-to-r from-synapse to-[#002F5C] text-white py-4 px-6 shadow-md relative z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-extrabold text-2xl tracking-wide flex items-center gap-2">
                <span class="text-white">
                    <svg class="w-10 h-10">
                        <use xlink:href="{{ asset('assets/sprite.svg#icon-main') }}" />
                    </svg>
                </span>
                SYNAPSE
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-white/80 hover:text-white transition text-sm font-medium">
                    Volver a la tienda
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div id="particles-js" class="absolute inset-0 z-0"></div>

        <div class="max-w-md w-full space-y-8 bg-white/90 backdrop-blur-sm p-10 rounded-3xl shadow-xl border border-white/50 opacity-95 relative z-10">
            {{ $slot }}
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Particles background
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof particlesJS !== 'undefined') {
                particlesJS('particles-js', {
                    particles: {
                        number: { value: 100, density: { enable: true, value_area: 1000 } },
                        color: { value: '#004689' },
                        shape: { type: 'circle' },
                        opacity: { value: 0.7, random: true },
                        size: { value: 3, random: true },
                        line_linked: { enable: true, distance: 180, color: '#004689', opacity: 0.5, width: 1 },
                        move: { enable: true, speed: 1.5, direction: 'none', random: true, out_mode: 'out' }
                    },
                    interactivity: {
                        detect_on: 'canvas',
                        events: { onhover: { enable: true, mode: 'grab' }, onclick: { enable: true, mode: 'push' }, resize: true },
                        modes: { grab: { distance: 140, line_linked: { opacity: 1 } }, push: { particles_nb: 4 } }
                    },
                    retina_detect: true
                });
            }
        });

        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const eyePaths = this.querySelectorAll('.eye');
                const eyeOffPaths = this.querySelectorAll('.eye-off');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyePaths.forEach(p => p.classList.add('hidden'));
                    eyeOffPaths.forEach(p => p.classList.remove('hidden'));
                } else {
                    input.type = 'password';
                    eyePaths.forEach(p => p.classList.remove('hidden'));
                    eyeOffPaths.forEach(p => p.classList.add('hidden'));
                }
            });
        });
    </script>
</body>
</html>

<!doctype html>
<html x-data="{ darkMode: localStorage.getItem('dark') === 'true', sidebarOpen: false }"
      x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
      x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#6366f1">

    <!-- PWA Meta Tags -->
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="msapplication-TileColor" content="#6366f1">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">

    <title>{{ config('app.name') }}</title>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 dark:bg-dark dark:text-gray-100">

    <!-- Header Mobile -->
    <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Menu Button -->
            <button @click="sidebarOpen = true"
                    class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 tap-highlight-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Logo/Brand -->
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">F</span>
                </div>
                <span class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ config('app.name') }}
                </span>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-2">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode"
                        class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 tap-highlight-none">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- User Avatar/Circle -->
                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-sm">
                        @auth('student')
                            {{ substr(auth()->guard('student')->user()->name, 0, 1) }}
                        @endauth
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay Mobile -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black bg-opacity-50 transition-opacity lg:hidden"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar Mobile -->
    <nav x-show="sidebarOpen"
         class="fixed inset-y-0 left-0 z-50 w-80 bg-white dark:bg-gray-900 shadow-xl transform transition-transform lg:hidden"
         x-transition:enter="transform ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">

        <!-- Header Sidebar -->
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="text-white">
                    <h2 class="text-xl font-bold">Olá!</h2>
                    <p class="text-blue-100">
                        @auth('student')
                            {{ auth()->guard('student')->user()->name }}
                        @endauth
                    </p>
                </div>
                <button @click="sidebarOpen = false"
                        class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 tap-highlight-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="px-4 py-6 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('student.dashboard') }}"
               class="block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-colors tap-highlight-none">
                📊 Dashboard
            </a>

            <!-- Treinos -->
            <a href="{{ route('student.workouts.index') }}"
               class="block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-colors tap-highlight-none">
                💪 Fitness
            </a>

            <!-- Aulas -->
            <a href="{{ route('student.class_bookings.index') }}"
               class="block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-colors tap-highlight-none">
                📅 Aulas
            </a>

            <!-- Perfil -->
            <a href="{{ route('student.profile') }}"
               class="block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-colors tap-highlight-none">
                👤 Perfil
            </a>

            <!-- Horários -->
            <a href="{{ route('student.class_schedules.index') }}"
               class="block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-colors tap-highlight-none">
                🕗 Horários
            </a>

            <!-- Separator -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

            <!-- Logout -->
            <form action="{{ route('student.logout') }}" method="POST" class="block">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 transition-colors tap-highlight-none">
                    🚪 Sair
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-4 px-4 pb-20 min-h-screen">
        @yield('content')
    </main>

    <!-- Bottom Navigation Mobile -->
    <nav class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 fixed bottom-0 left-0 right-0 z-30">
        <div class="grid grid-cols-5 h-16">
            <!-- Dashboard -->
            <a href="{{ route('student.dashboard') }}"
               class="flex flex-col items-center justify-center space-y-1 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors tap-highlight-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"/>
                </svg>
                <span class="text-xs font-medium">Início</span>
            </a>

            <!-- Fitness -->
            <a href="{{ route('student.workouts.index') }}"
               class="flex flex-col items-center justify-center space-y-1 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors tap-highlight-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-xs font-medium">Fitness</span>
            </a>

            <!-- Agendar (central) -->
            <a href="{{ route('student.class_bookings.index') }}"
               class="flex flex-col items-center justify-center space-y-1 text-white rounded-full w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg -mt-6 tap-highlight-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="text-xs font-bold">Agendar</span>
            </a>

            <!-- Aulas -->
            <a href="{{ route('student.class_bookings.index') }}"
               class="flex flex-col items-center justify-center space-y-1 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors tap-highlight-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs font-medium">Aulas</span>
            </a>

            <!-- Perfil -->
            <a href="{{ route('student.profile') }}"
               class="flex flex-col items-center justify-center space-y-1 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors tap-highlight-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs font-medium">Perfil</span>
            </a>
        </div>
    </nav>

    @stack('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.0/flowbite.min.js"></script>
</body>

</html>

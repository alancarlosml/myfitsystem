@php
    // Usar layout mobile apenas se for dispositivo móvel (verifica se variável existe e é true)
    $layout = (!empty($isMobile) && $isMobile === true) ? 'layouts.student-mobile' : 'layouts.app';
@endphp
@extends($layout)

@section('content')
<div x-data="classDetailsModal()" x-cloak>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Olá, {{ auth()->guard('student')->user()->name }}! 👋</h1>
                    <p class="mt-1 text-blue-100">Bem-vindo ao seu painel de controle</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-sm font-medium text-white">{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM') }}</p>
                        <p class="text-xs text-blue-100">{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('YYYY') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H19C20.11 23 21 22.11 21 21V9M19 9H14V4H19V9Z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Cards de Métricas Modernos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card de Aulas no Mês -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-white">
                                <p class="text-blue-100 text-sm font-medium">Aulas no Mês</p>
                                <h3 class="text-3xl font-bold text-white">{{ $classesThisMonth }}</h3>
                            </div>
                            <div class="bg-white/20 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ ucfirst(\Carbon\Carbon::now()->translatedFormat('F')) }}
                        </span>
                    </div>
                </div>

                <!-- Card de Aulas no Ano -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-white">
                                <p class="text-green-100 text-sm font-medium">Aulas no Ano</p>
                                <h3 class="text-3xl font-bold text-white">{{ $classesThisYear }}</h3>
                            </div>
                            <div class="bg-white/20 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Meta: {{ $classesThisYear * 0.9 }} aulas
                        </span>
                    </div>
                </div>

                <!-- Card de Sequência -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-white">
                                <p class="text-purple-100 text-sm font-medium">Sequência</p>
                                <h3 class="text-3xl font-bold text-white">{{ $streakDays }}</h3>
                            </div>
                            <div class="bg-white/20 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800">
                        <div class="flex items-center">
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full transition-all duration-1000" style="width: {{ min($streakDays * 2, 100) }}%"></div>
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">dias</span>
                        </div>
                    </div>
                </div>

                <!-- Card de Troféus -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-white">
                                <p class="text-yellow-100 text-sm font-medium">Conquistas</p>
                                <h3 class="text-3xl font-bold text-white">{{ $achievements }}</h3>
                            </div>
                            <div class="bg-white/20 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            🎖️ {{ ceil($achievements/3) }} badges
                        </span>
                    </div>
                </div>
            </div>

            <!-- Seções de Conteúdo -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Próximas Aulas -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                            <h2 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Próximas Aulas
                            </h2>
                        </div>
                        <div class="p-6 space-y-4 bg-white dark:bg-gray-800">
                            @forelse($upcomingClasses as $booking)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg hover:shadow-md transition-all duration-200 gap-4">
                                    <div class="flex-shrink-0 w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center">
                                        @switch(strtolower($booking->classSchedule->modality->name ?? ''))
                                            @case('musculação')
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                                </svg>
                                                @break
                                            @case('yoga')
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                                @break
                                            @default
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                                </svg>
                                        @endswitch
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $booking->classSchedule->modality->name ?? 'Aula' }}</h3>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 self-start">
                                                {{ \Carbon\Carbon::parse($booking->classSchedule->class_date)->locale('pt_BR')->isoFormat('dddd, HH:mm') }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $booking->classSchedule->description ?? 'Sala principal' }}</p>
                                    </div>
                                    <div class="w-full sm:w-auto">
                                        <button @click="showClassDetails({{ $booking->id }}, '{{ $booking->classSchedule->modality->name ?? 'Aula' }}', '{{ \Carbon\Carbon::parse($booking->classSchedule->class_date)->locale('pt_BR')->isoFormat('dddd, DD/MM/YYYY') }}', '{{ \Carbon\Carbon::parse($booking->classSchedule->start_time)->format('H:i') }}', '{{ \Carbon\Carbon::parse($booking->classSchedule->end_time)->format('H:i') }}', '{{ $booking->classSchedule->class_room ?? 'Principal' }}', '{{ $booking->classSchedule->instructor ?? 'Equipe FitSystem' }}', '{{ $booking->classSchedule->description ?? '' }}')" 
                                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-medium">
                                            Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <h4 class="text-gray-900 dark:text-white font-medium">Nenhuma aula agendada</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Suas próximas aulas aparecerão aqui</p>
                                </div>
                            @endforelse

                            <!-- Ver Todas -->
                            <div class="text-center pt-4">
                                <a href="{{ route('student.class_bookings.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver Todas as Aulas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Painel Lateral -->
                <div class="space-y-6">
                    <!-- Notificações Recentes -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-pink-500 p-6">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                Notificações
                            </h2>
                        </div>
                        <div class="p-4 space-y-3 bg-white dark:bg-gray-800 max-h-80 overflow-y-auto">
                            @forelse($notifications as $notification)
                                <div class="flex items-start space-x-3 p-3 {{ ($notification['type'] ?? 'info') == 'success' ? 'bg-green-50 dark:bg-green-900/20' : (($notification['type'] ?? 'info') == 'warning' ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-blue-50 dark:bg-blue-900/20') }} rounded-lg">
                                    <div class="flex-shrink-0 w-8 h-8 {{ ($notification['type'] ?? 'info') == 'success' ? 'bg-green-500' : (($notification['type'] ?? 'info') == 'warning' ? 'bg-yellow-500' : 'bg-blue-500') }} rounded-full flex items-center justify-center">
                                        @if(($notification['type'] ?? 'info') == 'success')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif(($notification['type'] ?? 'info') == 'warning')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $notification['title'] ?? 'Notificação' }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{Str::limit($notification['message'] ?? '', 80)}}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação recente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Ações Rápidas
                        </h2>

                        <div class="space-y-3">
                            <a href="{{ route('student.workouts.index') }}" class="flex items-center p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors duration-200 group">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mr-3 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">Meus Treinos</span>
                            </a>

                            <a href="{{ route('student.class_schedules.index') }}" class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200 group">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">Agendar Aulas</span>
                            </a>

                            <a href="{{ route('student.profile') }}" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors duration-200 group">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-3 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">Meu Perfil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de Progresso -->
            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Seu Progresso Físico
                        </h2>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800">
                        @if(isset($latestAssessment) && $latestAssessment)
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Peso</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $latestAssessment->weight }} <span class="text-xs font-normal">kg</span></p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Gordura</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $latestAssessment->body_fat_percentage ?? '-' }} <span class="text-xs font-normal">%</span></p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Músculo</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $latestAssessment->muscle_mass_percentage ?? '-' }} <span class="text-xs font-normal">%</span></p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Data</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($latestAssessment->assessment_date)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('student.physical_assessments.index') }}" class="inline-flex items-center px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                    Ver Detalhes Completos
                                </a>
                            </div>
                        @else
                            <div class="flex items-center justify-center py-8">
                                <div class="text-center max-w-md mx-auto">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">Sem histórico recente</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Suas avaliações físicas aparecerão aqui.</p>
                                    <a href="{{ route('student.physical_assessments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Solicitar Avaliação
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer/Espaçamento -->
            <div class="h-8"></div>
        </div>
    </div>

    <!-- Modal de Detalhes da Aula -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="closeModal()"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
         style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full shadow-2xl"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Header do Modal -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Detalhes da Aula
                    </h3>
                    <button @click="closeModal()" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="p-6 space-y-4">
                <!-- Modalidade -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-4">
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="selectedClass.modality"></h4>
                </div>

                <!-- Informações da Aula -->
                <div class="space-y-3">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Data</p>
                            <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedClass.date"></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Horário</p>
                            <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedClass.startTime + ' - ' + selectedClass.endTime"></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Local</p>
                            <p class="font-semibold text-gray-900 dark:text-white" x-text="'Sala ' + selectedClass.room"></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Instrutor</p>
                            <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedClass.instructor"></p>
                        </div>
                    </div>

                    <div x-show="selectedClass.description" class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Descrição</p>
                            <p class="text-gray-900 dark:text-white" x-text="selectedClass.description"></p>
                        </div>
                    </div>
                </div>

                <!-- Mensagem de Feedback -->
                <div x-show="feedbackMessage" 
                     x-transition
                     :class="feedbackType === 'success' ? 'bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200' : 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200'"
                     class="p-4 rounded-lg">
                    <p x-text="feedbackMessage"></p>
                </div>
            </div>

            <!-- Footer do Modal -->
            <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-b-2xl flex flex-col sm:flex-row gap-3">
                <button @click="closeModal()" 
                        class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors font-medium">
                    Fechar
                </button>
                <button @click="cancelBooking()" 
                        :disabled="isCanceling"
                        :class="isCanceling ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700'"
                        class="flex-1 px-6 py-3 text-white rounded-lg transition-colors font-medium flex items-center justify-center">
                    <svg x-show="isCanceling" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isCanceling ? 'Cancelando...' : 'Cancelar Agendamento'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('classDetailsModal', () => ({
            showModal: false,
            isCanceling: false,
            feedbackMessage: '',
            feedbackType: '',
            selectedClass: {
                id: null,
                modality: '',
                date: '',
                startTime: '',
                endTime: '',
                room: '',
                instructor: '',
                description: ''
            },

            showClassDetails(id, modality, date, startTime, endTime, room, instructor, description) {
                this.selectedClass = {
                    id: id,
                    modality: modality,
                    date: date,
                    startTime: startTime,
                    endTime: endTime,
                    room: room,
                    instructor: instructor,
                    description: description
                };
                this.feedbackMessage = '';
                this.showModal = true;
            },

            closeModal() {
                this.showModal = false;
                setTimeout(() => {
                    this.feedbackMessage = '';
                    this.feedbackType = '';
                }, 300);
            },

            async cancelBooking() {
                if (!confirm('Tem certeza que deseja cancelar este agendamento?')) {
                    return;
                }

                this.isCanceling = true;
                this.feedbackMessage = '';

                try {
                    const response = await fetch(`/app/cancelar-reserva/${this.selectedClass.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        this.feedbackType = 'success';
                        this.feedbackMessage = 'Agendamento cancelado com sucesso!';
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        this.feedbackType = 'error';
                        this.feedbackMessage = result.message || 'Erro ao cancelar agendamento';
                    }
                } catch (error) {
                    console.error('Erro ao cancelar agendamento:', error);
                    this.feedbackType = 'error';
                    this.feedbackMessage = 'Erro ao cancelar agendamento. Tente novamente.';
                }

                this.isCanceling = false;
            }
        }))
    });
</script>
@endsection

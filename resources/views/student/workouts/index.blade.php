@php
    // Usar layout mobile apenas se for dispositivo móvel
    $layout = (!empty($isMobile) && $isMobile === true) ? 'layouts.student-mobile' : 'layouts.app';
@endphp
@extends($layout)

@section('content')
<!-- Conteúdo da página -->
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Olá, {{ auth()->guard('student')->user()->name }}! 💪</h1>
                    <p class="mt-1 text-blue-100">Treinos personalizados e exercícios para seu progresso</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-sm font-medium text-white">{{ $workouts->count() }} treinos ativos</p>
                        <p class="text-xs text-blue-100">Prepare-se para treinar!</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Rápidos -->
        <!-- Stats Rápidos -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-red-100 text-xs md:text-sm font-medium">Total Treinos</p>
                        <p class="text-lg font-bold">{{ $workouts->count() }}</p>
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-yellow-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-orange-100 text-xs md:text-sm font-medium">Esta Semana</p>
                        <p class="text-lg font-bold">{{ $workouts->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-green-100 text-xs md:text-sm font-medium">Este Mês</p>
                        <p class="text-lg font-bold">{{ $workouts->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-blue-100 text-xs md:text-sm font-medium">Exercícios</p>
                        @php $totalExercises = $workouts->sum(function($workout) { return $workout->exercise ? 1 : 0; }); @endphp
                        <p class="text-lg font-bold">{{ $totalExercises }}</p>
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filters e Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Seus Treinos</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        Trainer: {{ auth()->guard('student')->user()->name }}
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <div class="relative w-full sm:w-auto">
                        <input type="text" placeholder="Buscar treinos..."
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-300">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <select class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Todos os treinos</option>
                        <option value="iniciante">Iniciante</option>
                        <option value="intermediario">Intermediário</option>
                        <option value="avancado">Avançado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid de Cards de Treino -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8 mb-8">
            @forelse($workouts as $workout)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <!-- Header do Card com Gradiente -->
                    <div class="bg-gradient-to-r {{ rand(0, 1) ? 'from-blue-600 to-indigo-600' : 'from-purple-600 to-pink-600' }} p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-white">
                                <h3 class="text-xl font-bold text-white mb-1">
                                    {{ $workout->exercise ? $workout->exercise->name : 'Treino Personalizado' }}
                                </h3>
                                <p class="text-white/80 text-sm">
                                    Professor: {{ $workout->user ? $workout->user->name : 'Equipe FitSystem' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                        {{ \Carbon\Carbon::parse($workout->created_at)->locale('pt_BR')->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Info do Treino -->
                        <div class="grid grid-cols-3 gap-4 text-white text-center">
                            <div>
                                <p class="text-sm text-white/80">Criado em</p>
                                <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($workout->created_at)->format('d/m') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/80">Status</p>
                                <p class="text-lg font-bold">Ativo</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/80">Tipo</p>
                                <p class="text-lg font-bold">{{ $workout->exercise ? 'Individual' : 'Grupo' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo do Card -->
                    <div class="p-6">
                        <!-- Descrição do Treino -->
                        @if($workout->exercise && $workout->exercise->description)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Sobre este exercício</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                                    {{ $workout->exercise->description }}
                                </p>
                            </div>
                        @else
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Treino Personalizado</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Treino criado especialmente para você por nossa equipe de instrutores.
                                </p>
                            </div>
                        @endif

                        <!-- Informações Técnicas -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <div class="ml-2">
                                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Nível</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            @switch((string)$workout->exercise_id % 3)
                                                @case('1') Iniciante @break
                                                @case('2') Intermediário @break
                                                @default Avançado
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="ml-2">
                                        <p class="text-xs text-green-600 dark:text-green-400 font-medium">Duração</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ rand(30, 90) }}min</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botão de Ação -->
                        <div class="flex space-x-3">
                            <a href="{{ route('student.workouts.start', $workout->id) }}" class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white py-3 px-4 rounded-lg transition-all duration-200 font-medium group-hover:scale-105 group-hover:shadow-lg text-center">
                                🚀 Começar Treino
                            </a>
                            <button class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group-hover:scale-105 group-hover:shadow-lg">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Estado Vazio -->
                <div class="col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-12 text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhum treino encontrado</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">Você ainda não tem treinos atribuídos. Contate seu professor para receber novos treinos personalizados.</p>
                        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                            Ir para Dashboard
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Footer com Call-to-Action -->
        <div class="text-center mt-8">
            <div class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Precisa de um treino personalizado?</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">Conte com nossos melhores professores para criar o plano ideal para você.</p>
                <button class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-8 py-4 rounded-lg text-lg font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                    💬 Falar com Professor
                </button>
            </div>
        </div>
    </div>
@endsection

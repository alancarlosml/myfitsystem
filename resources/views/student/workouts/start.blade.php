@php
    // Usar layout mobile apenas se for dispositivo móvel
    $layout = (!empty($isMobile) && $isMobile === true) ? 'layouts.student-mobile' : 'layouts.app';
@endphp
@extends($layout)

@section('content')
    <!-- Header com Progresso Global -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-700 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button onclick="history.back()" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold">🚀 Treino: {{ $workout->exercise->name ?? 'Treino Personalizado' }}</h1>
                        <p class="text-blue-100">{{ count($exercises) }} exercícios • Progresso Global</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Cronômetro Global -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="global-timer" class="font-mono font-medium">00:00:00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de Progresso Global -->
            <div class="mt-6">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span>Progresso do Treino</span>
                    <span id="progress-text">0 de {{ count($exercises) }} exercícios</span>
                </div>
                <div class="w-full bg-white/20 rounded-full h-3">
                    <div id="global-progress" class="bg-white h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen"
         x-data="{ 
             ...workoutSession({{ count($exercises) }}, {{ json_encode($exercises) }}),
             viewMode: null,
             getYouTubeEmbedUrl(url) {
                 if (!url) return '';
                 let videoId = '';
                 if (url.includes('youtube.com/watch?v=')) {
                     videoId = url.split('v=')[1]?.split('&')[0];
                 } else if (url.includes('youtu.be/')) {
                     videoId = url.split('youtu.be/')[1]?.split('?')[0];
                 } else if (url.includes('youtube.com/embed/')) {
                     return url;
                 }
                 if (videoId) {
                     return `https://www.youtube.com/embed/${videoId}`;
                 }
                 return url;
             },
             getCurrentViewMode() {
                 if (this.viewMode) return this.viewMode;
                 // Auto-select: prefer video if available, otherwise photo
                 if (this.currentExercise.youtube_video_url) return 'video';
                 if (this.currentExercise.picture) return 'photo';
                 return null;
             }
         }"
         x-init="viewMode = getCurrentViewMode()">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Barra de Steps dos Exercícios -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Sequência de Exercícios</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Clique em um exercício para navegar
                    </span>
                </div>
                <div class="flex space-x-4 overflow-x-auto pb-4" id="exercise-steps">
                    <template x-for="(exercise, index) in exercises" :key="exercise.id">
                        <button @click="goToExercise(index)"
                                :class="index === currentExerciseIndex
                                        ? 'bg-blue-500 text-white scale-105'
                                        : index < currentExerciseIndex
                                        ? 'bg-green-500 text-white'
                                        : 'bg-gray-200 text-gray-600 hover:bg-gray-300'"
                                class="flex-shrink-0 w-20 h-20 rounded-full flex items-center justify-center font-medium text-sm transition-all duration-300"
                                :disabled="index > currentExerciseIndex">
                            <span x-text="index + 1"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Conteúdo Principal - Exercício Atual -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Exercício Atual (Centro) -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">

                        <!-- Header do Exercício -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-3xl font-bold text-white" x-text="currentExercise.name"></h2>
                                    <p class="text-blue-100" x-text="currentExercise.category"></p>
                                </div>
                                <div class="text-right">
                                    <div class="text-4xl mb-2">
                                        <span x-show="currentSet <= currentExercise.sets">🔥</span>
                                        <span x-show="currentSet > currentExercise.sets">✅</span>
                                    </div>
                                    <p class="text-blue-100 text-sm">
                                        Série <span x-text="currentSet"></span> de <span x-text="currentExercise.sets"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Conteúdo do Exercício -->
                        <div class="p-4 sm:p-8">

                            <!-- Seletor de Visualização (Vídeo/Foto) -->
                            <div class="mb-4 flex flex-wrap gap-2 justify-center" x-show="(currentExercise.youtube_video_url && currentExercise.picture)">
                                <button @click="viewMode = 'video'" 
                                        :class="getCurrentViewMode() === 'video' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                        class="px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2"
                                        x-show="currentExercise.youtube_video_url">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Vídeo</span>
                                </button>
                                <button @click="viewMode = 'photo'" 
                                        :class="getCurrentViewMode() === 'photo' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                        class="px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2"
                                        x-show="currentExercise.picture">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Foto</span>
                                </button>
                            </div>

                            <!-- Vídeo do YouTube -->
                            <div class="mb-6" x-show="getCurrentViewMode() === 'video'">
                                <div class="w-full max-w-2xl mx-auto">
                                    <div class="relative aspect-video bg-black rounded-xl overflow-hidden shadow-lg">
                                        <iframe 
                                            :src="getYouTubeEmbedUrl(currentExercise.youtube_video_url)"
                                            class="absolute top-0 left-0 w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>

                            <!-- Imagem do Exercício -->
                            <div class="mb-6 text-center" x-show="getCurrentViewMode() === 'photo'">
                                <div class="w-full max-w-md mx-auto">
                                    <img :src="currentExercise.picture ? '{{ asset('storage/') }}/' + currentExercise.picture : ''" 
                                         :alt="currentExercise.name"
                                         class="w-full h-auto rounded-xl shadow-lg object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full aspect-video bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-xl flex items-center justify-center hidden">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p>Imagem não disponível</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Placeholder quando não há vídeo nem foto -->
                            <div class="mb-6 text-center" x-show="!currentExercise.youtube_video_url && !currentExercise.picture">
                                <div class="w-full max-w-md mx-auto aspect-video bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-xl flex items-center justify-center">
                                    <div class="text-gray-400 dark:text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p>Sem mídia disponível</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Descrição -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Como executar</h3>
                                <p class="text-gray-600 dark:text-gray-300" x-text="currentExercise.description"></p>
                            </div>

                            <!-- Configuração da Série -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400" x-text="currentExercise.reps"></div>
                                    <div class="text-sm text-red-600 dark:text-red-400">Repetições</div>
                                </div>
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        <span x-text="Math.floor(currentExercise.rest_time / 60)"></span>:<span x-text="(currentExercise.rest_time % 60).toString().padStart(2, '0')"></span>
                                    </div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Descanso</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        <span x-text="currentExercise.sets - currentSet + 1"></span>
                                    </div>
                                    <div class="text-sm text-green-600 dark:text-green-400">Séries restantes</div>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400" x-text="currentExercise.duration"></div>
                                    <div class="text-sm text-purple-600 dark:text-purple-400">min/série</div>
                                </div>
                            </div>

                            <!-- Timer de Descanso (quando ativo) -->
                            <div x-show="restTimeActive" class="mb-6 bg-orange-50 dark:bg-orange-900/20 p-6 rounded-xl border border-orange-200 dark:border-orange-800">
                                <div class="text-center">
                                    <h4 class="text-lg font-semibold text-orange-800 dark:text-orange-200 mb-2">Tempo de Descanso</h4>
                                    <div class="text-6xl font-mono font-bold text-orange-600 dark:text-orange-400 mb-4" x-text="formattedRestTime"></div>
                                    <button @click="skipRest()" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                        Pular Descanso
                                    </button>
                                </div>
                            </div>

                            <!-- Controle da Série -->
                            <div class="flex items-center justify-center space-x-4">
                                <button @click="previousExercise()"
                                        :disabled="currentExerciseIndex === 0"
                                        :class="currentExerciseIndex === 0
                                               ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                               : 'bg-blue-500 hover:bg-blue-600 text-white'"
                                        class="flex items-center space-x-2 px-6 py-3 rounded-lg font-medium transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span>Anterior</span>
                                </button>

                                <button @click="completeCurrentExercise()"
                                        class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-medium text-lg transition-colors flex items-center space-x-3">
                                    <span x-show="!isCompletingExercise">✅ Completar Exercício</span>
                                    <span x-show="isCompletingExercise">
                                        <svg class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </span>
                                    <span x-show="isCompletingExercise">Salvando...</span>
                                </button>

                                <button @click="nextExercise()"
                                        :disabled="currentExerciseIndex >= exercises.length - 1"
                                        :class="currentExerciseIndex >= exercises.length - 1
                                               ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                               : 'bg-blue-500 hover:bg-blue-600 text-white'"
                                        class="flex items-center space-x-2 px-6 py-3 rounded-lg font-medium transition-colors">
                                    <span>Próximo</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Painel Lateral - Lista de Exercícios -->
                <div class="space-y-6">

                    <!-- Lista de Exercícios -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Lista de Exercícios</h3>

                        <div class="space-y-3">
                            <template x-for="(exercise, index) in exercises" :key="exercise.id">
                                <div @click="goToExercise(index)"
                                     :class="index === currentExerciseIndex
                                            ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-300 dark:border-blue-600'
                                            : index < currentExerciseIndex
                                            ? 'bg-green-50 dark:bg-green-900/30 border-green-300 dark:border-green-600'
                                            : 'bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600'"
                                     class="p-4 rounded-xl border-2 cursor-pointer transition-all duration-300 hover:shadow-md">

                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900 dark:text-white" x-text="exercise.name"></h4>
                                        <div class="flex items-center space-x-1">
                                            <span x-show="index < currentExerciseIndex" class="text-green-500 text-lg">✓</span>
                                            <span x-show="index === currentExerciseIndex" class="text-blue-500 text-lg">▶</span>
                                            <span x-text="exercise.sets + 'x' + exercise.reps" class="text-sm text-gray-500 dark:text-gray-400"></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                        <span x-text="exercise.category"></span>
                                        <span x-text="exercise.duration + ' min'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Estatísticas Rápidas -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Estatísticas</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Tempo decorrido</span>
                                <span id="elapsed-time" class="font-medium">00:00:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Exercícios completados</span>
                                <span x-text="completedExercisesCount + ' de ' + exercises.length"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Séries realizadas</span>
                                <span x-text="totalSetsCompleted"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Progresso total</span>
                                <span x-text="(completedExercisesCount / exercises.length * 100).toFixed(0) + '%'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="space-y-3">
                        <button @click="finishWorkout()"
                                class="w-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white py-3 px-6 rounded-xl font-medium transition-colors">
                            🚩 Finalizar Treino
                        </button>

                        <button @click="pauseWorkout()"
                                class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white py-3 px-6 rounded-xl font-medium transition-colors">
                            ⏸️ <span x-text="isPaused ? 'Retomar Treino' : 'Pausar Treino'"></span>
                        </button>
                    </div>

                </div>

            </div>
        </div>

        <!-- Modal de Confirmação de Finalização -->
        <div x-show="showFinishModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md w-full mx-4">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Finalizar Treino?</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">
                        Você completou <span x-text="completedExercisesCount"></span> de <span x-text="exercises.length"></span> exercícios.
                        <br>Tempo decorrido: <span id="finish-timer">00:00:00</span>
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button @click="showFinishModal = false"
                            class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 py-3 px-6 rounded-xl font-medium transition-colors">
                        Cancelar
                    </button>
                    <button @click="confirmFinishWorkout()"
                            :class="isFinishingWorkout ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600'"
                            :disabled="isFinishingWorkout"
                            class="flex-1 text-white py-3 px-6 rounded-xl font-medium transition-colors flex items-center justify-center">
                        <span x-show="!isFinishingWorkout">✅ Finalizar Treino</span>
                        <span x-show="isFinishingWorkout">Salvando...</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function workoutSession(totalExercises, exercisesData) {
            return {
                exercises: exercisesData,
                totalExercises: totalExercises,
                currentExerciseIndex: 0,
                currentExercise: exercisesData[0],
                currentSet: 1,
                completedExercisesCount: 0,
                totalSetsCompleted: 0,
                isPaused: false,
                isCompletingExercise: false,
                isFinishingWorkout: false,
                showFinishModal: false,
                restTimeActive: false,
                restTimeRemaining: 0,
                formattedRestTime: '00:00',
                workoutStartTime: null,
                timers: {},

                init() {
                    this.workoutStartTime = new Date();
                    this.startGlobalTimer();

                    // Load progress from LocalStorage (optional persistence)
                    this.loadProgress();
                },

                startGlobalTimer() {
                    setInterval(() => {
                        if (!this.isPaused && this.workoutStartTime) {
                            const elapsed = Math.floor((new Date() - this.workoutStartTime) / 1000);
                            const hours = Math.floor(elapsed / 3600);
                            const minutes = Math.floor((elapsed % 3600) / 60);
                            const seconds = elapsed % 60;

                            document.getElementById('global-timer').textContent =
                                hours.toString().padStart(2, '0') + ':' +
                                minutes.toString().padStart(2, '0') + ':' +
                                seconds.toString().padStart(2, '0');

                            document.getElementById('elapsed-time').textContent =
                                hours.toString().padStart(2, '0') + ':' +
                                minutes.toString().padStart(2, '0') + ':' +
                                seconds.toString().padStart(2, '0');
                        }
                    }, 1000);
                },

                goToExercise(index) {
                    if (index <= this.completedExercisesCount) {
                        this.currentExerciseIndex = index;
                        this.currentExercise = this.exercises[index];
                        this.currentSet = 1; // Reset to first set
                        this.stopRestTimer();
                        // Reset view mode for new exercise
                        this.viewMode = null;
                        this.updateProgress();
                    }
                },

                previousExercise() {
                    if (this.currentExerciseIndex > 0) {
                        this.currentExerciseIndex--;
                        this.currentExercise = this.exercises[this.currentExerciseIndex];
                        this.currentSet = 1;
                        this.stopRestTimer();
                        // Reset view mode for new exercise
                        this.viewMode = null;
                        this.updateProgress();
                    }
                },

                nextExercise() {
                    if (this.currentExerciseIndex < this.exercises.length - 1) {
                        this.currentExerciseIndex++;
                        this.currentExercise = this.exercises[this.currentExerciseIndex];
                        this.currentSet = 1;
                        this.stopRestTimer();
                        // Reset view mode for new exercise
                        this.viewMode = null;
                        this.updateProgress();
                    }
                },

                async completeCurrentExercise() {
                    this.isCompletingExercise = true;

                    try {
                        // Log the exercise completion
                        await this.logExerciseCompletion();

                        // Mark exercise as completed
                        this.completedExercisesCount = Math.max(this.completedExercisesCount, this.currentExerciseIndex + 1);
                        this.totalSetsCompleted += this.currentSet;

                        // Start rest timer if not the last exercise
                        if (this.currentExerciseIndex < this.exercises.length - 1) {
                            this.startRestTimer();
                        } else {
                            // If it's the last exercise, show finish modal
                            this.showFinishModal = true;
                            document.getElementById('finish-timer').textContent = document.getElementById('elapsed-time').textContent;
                        }

                        this.updateProgress();

                    } catch (error) {
                        console.error('Erro ao completar exercício:', error);
                        // Could show error message here
                    }

                    this.isCompletingExercise = false;
                },

                startRestTimer() {
                    this.restTimeActive = true;
                    this.restTimeRemaining = this.currentExercise.rest_time;

                    this.timers.restTimer = setInterval(() => {
                        if (this.restTimeRemaining > 0 && !this.isPaused) {
                            this.restTimeRemaining--;

                            const minutes = Math.floor(this.restTimeRemaining / 60);
                            const seconds = this.restTimeRemaining % 60;
                            this.formattedRestTime = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
                        } else if (this.restTimeRemaining <= 0) {
                            this.stopRestTimer();
                            // Auto-advance to next set if available, or next exercise
                            if (this.currentSet < this.currentExercise.sets) {
                                this.currentSet++;
                            } else {
                                this.nextExercise();
                            }
                        }
                    }, 1000);

                    this.restTimeRemaining--; // Start immediately
                    const minutes = Math.floor(this.restTimeRemaining / 60);
                    const seconds = this.restTimeRemaining % 60;
                    this.formattedRestTime = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
                },

                skipRest() {
                    this.stopRestTimer();
                    // Auto-advance to next set if available, or next exercise
                    if (this.currentSet < this.currentExercise.sets) {
                        this.currentSet++;
                    } else {
                        this.nextExercise();
                    }
                },

                stopRestTimer() {
                    this.restTimeActive = false;
                    this.restTimeRemaining = 0;
                    this.formattedRestTime = '00:00';

                    if (this.timers.restTimer) {
                        clearInterval(this.timers.restTimer);
                        this.timers.restTimer = null;
                    }
                },

                pauseWorkout() {
                    this.isPaused = !this.isPaused;
                    if (this.isPaused) {
                        this.stopRestTimer();
                    }
                },

                finishWorkout() {
                    this.showFinishModal = true;
                    document.getElementById('finish-timer').textContent = document.getElementById('elapsed-time').textContent;
                },

                async confirmFinishWorkout() {
                    this.isFinishingWorkout = true;

                    // Save final progress
                    await this.saveFinalProgress();

                    // Redirect back to workouts page
                    window.location.href = '{{ route("student.workouts.index") }}';
                },

                async logExerciseCompletion() {
                    const formData = new FormData();
                    formData.append('exercise_id', this.currentExercise.id.startsWith('demo-') ? '' : this.currentExercise.id);
                    formData.append('completed_sets', this.currentSet);
                    formData.append('completed_reps', this.currentExercise.reps);
                    formData.append('exercise_duration', this.currentExercise.duration * 60); // Convert to seconds
                    formData.append('notes', `Série ${this.currentSet} de ${this.currentExercise.sets} completada via treino iniciado`);

                    const response = await fetch('{{ route("student.workouts.log", ":workoutId") }}'.replace(':workoutId', '{{ $workout->id }}'), {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao salvar progresso');
                    }
                },

                async saveFinalProgress() {
                    // This could save workout completion time and final stats
                    // For now, just log completion
                    console.log('Treino finalizado com sucesso!');
                },

                updateProgress() {
                    const progressPercent = (this.completedExercisesCount / this.exercises.length) * 100;
                    document.getElementById('global-progress').style.width = progressPercent + '%';
                    document.getElementById('progress-text').textContent = this.completedExercisesCount + ' de ' + this.exercises.length + ' exercícios';
                },

                saveProgress() {
                    localStorage.setItem('workout_progress_{{ $workout->id }}', JSON.stringify({
                        currentExerciseIndex: this.currentExerciseIndex,
                        currentSet: this.currentSet,
                        completedExercisesCount: this.completedExercisesCount,
                        totalSetsCompleted: this.totalSetsCompleted,
                        timestamp: Date.now()
                    }));
                },

                loadProgress() {
                    const saved = localStorage.getItem('workout_progress_{{ $workout->id }}');
                    if (saved) {
                        const progress = JSON.parse(saved);
                        // Check if it's recent (less than 24 hours)
                        if (Date.now() - progress.timestamp < 24 * 60 * 60 * 1000) {
                            // Could restore progress here if needed
                        }
                    }
                },

            }
        }
    </script>
@endsection

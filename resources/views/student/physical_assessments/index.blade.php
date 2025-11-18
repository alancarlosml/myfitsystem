<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Minhas Avaliações Físicas</h1>
                    <p class="mt-1 text-blue-100">Acompanhe seu progresso físico e evolução</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-xs text-blue-200">Total de avaliações</p>
                        <p class="text-2xl font-bold text-white">{{ $assessments->total() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-success />

            @if($assessments->count() > 0)
                <!-- Grid de Cards de Avaliações -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($assessments as $assessment)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                            <!-- Header do Card com Gradiente -->
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-white">
                                        <h3 class="text-xl font-bold text-white mb-1">
                                            Avaliação Física
                                        </h3>
                                        <p class="text-white/80 text-sm">
                                            {{ \Carbon\Carbon::parse($assessment->assessment_date)->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Indicadores Principais -->
                                <div class="grid grid-cols-2 gap-3 mt-4">
                                    @if($assessment->weight)
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                                            <p class="text-xs text-white/80 mb-1">Peso</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($assessment->weight, 1, ',', '.') }} kg</p>
                                        </div>
                                    @endif

                                    @if($assessment->bmi)
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                                            <p class="text-xs text-white/80 mb-1">IMC</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($assessment->bmi, 1, ',', '.') }}</p>
                                        </div>
                                    @endif

                                    @if($assessment->body_fat_percentage)
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                                            <p class="text-xs text-white/80 mb-1">% Gordura</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($assessment->body_fat_percentage, 1, ',', '.') }}%</p>
                                        </div>
                                    @endif

                                    @if($assessment->muscle_mass_percentage)
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                                            <p class="text-xs text-white/80 mb-1">% Músculo</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($assessment->muscle_mass_percentage, 1, ',', '.') }}%</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Conteúdo do Card -->
                            <div class="p-6">
                                <!-- Informações Adicionais -->
                                <div class="space-y-3 mb-4">
                                    @if($assessment->user)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span>Avaliado por: <strong>{{ $assessment->user->name }}</strong></span>
                                        </div>
                                    @endif

                                    @if($assessment->bmi_category)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <span>Classificação IMC: <strong>{{ $assessment->bmi_category }}</strong></span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Botão de Ação -->
                                <a href="{{ route('student.physical_assessments.show', $assessment->id) }}" 
                                   class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white py-3 px-4 rounded-lg transition-all duration-200 font-medium group-hover:scale-105 group-hover:shadow-lg text-center inline-flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                @if($assessments->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $assessments->links() }}
                    </div>
                @endif
            @else
                <!-- Estado Vazio -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-12 text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhuma avaliação física encontrada</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">Você ainda não possui avaliações físicas registradas. Entre em contato com seu professor para agendar uma avaliação.</p>
                        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                            Voltar para Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>


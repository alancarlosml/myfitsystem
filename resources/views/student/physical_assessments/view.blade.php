@php
    // Buscar avaliação anterior para comparação
    $previousAssessment = \App\Models\PhysicalAssessment::where('student_id', $assessment->student_id)
        ->where('establishment_id', $assessment->establishment_id)
        ->where('assessment_date', '<', $assessment->assessment_date)
        ->orderBy('assessment_date', 'desc')
        ->first();
@endphp

<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Avaliação Física</h1>
                    <p class="mt-1 text-blue-100">{{ \Carbon\Carbon::parse($assessment->assessment_date)->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('student.physical_assessments.index') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar para Lista
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informações da Avaliação -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações da Avaliação</h3>

                        <div class="space-y-4">
                            <div>
                                <strong class="text-gray-700 dark:text-gray-300 text-sm">Data da Avaliação:</strong>
                                <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($assessment->assessment_date)->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}</p>
                            </div>

                            @if($assessment->user)
                                <div>
                                    <strong class="text-gray-700 dark:text-gray-300 text-sm">Avaliado por:</strong>
                                    <p class="text-gray-900 dark:text-white">{{ $assessment->user->name }}</p>
                                </div>
                            @endif

                            @if($assessment->establishment)
                                <div>
                                    <strong class="text-gray-700 dark:text-gray-300 text-sm">Estabelecimento:</strong>
                                    <p class="text-gray-900 dark:text-white">{{ $assessment->establishment->name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($previousAssessment)
                        <!-- Comparação com Avaliação Anterior -->
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl shadow-lg p-6 border border-emerald-200 dark:border-emerald-800">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                Comparação com Avaliação Anterior
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{ \Carbon\Carbon::parse($previousAssessment->assessment_date)->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}
                            </p>

                            <div class="space-y-3">
                                @if($assessment->weight && $previousAssessment->weight)
                                    @php
                                        $weightDiff = $assessment->weight - $previousAssessment->weight;
                                        $weightDiffFormatted = number_format(abs($weightDiff), 1, ',', '.');
                                    @endphp
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Peso:</span>
                                        <span class="font-bold {{ $weightDiff > 0 ? 'text-red-600' : ($weightDiff < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                            {{ $weightDiff > 0 ? '+' : ($weightDiff < 0 ? '-' : '') }}{{ $weightDiffFormatted }} kg
                                        </span>
                                    </div>
                                @endif

                                @if($assessment->bmi && $previousAssessment->bmi)
                                    @php
                                        $bmiDiff = $assessment->bmi - $previousAssessment->bmi;
                                        $bmiDiffFormatted = number_format(abs($bmiDiff), 1, ',', '.');
                                    @endphp
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">IMC:</span>
                                        <span class="font-bold {{ $bmiDiff > 0 ? 'text-red-600' : ($bmiDiff < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                            {{ $bmiDiff > 0 ? '+' : ($bmiDiff < 0 ? '-' : '') }}{{ $bmiDiffFormatted }}
                                        </span>
                                    </div>
                                @endif

                                @if($assessment->body_fat_percentage && $previousAssessment->body_fat_percentage)
                                    @php
                                        $fatDiff = $assessment->body_fat_percentage - $previousAssessment->body_fat_percentage;
                                        $fatDiffFormatted = number_format(abs($fatDiff), 1, ',', '.');
                                    @endphp
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">% Gordura:</span>
                                        <span class="font-bold {{ $fatDiff > 0 ? 'text-red-600' : ($fatDiff < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                            {{ $fatDiff > 0 ? '+' : ($fatDiff < 0 ? '-' : '') }}{{ $fatDiffFormatted }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Dados da Avaliação -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Indicadores Principais -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Indicadores Principais</h3>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @if($assessment->weight)
                                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($assessment->weight, 1, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Peso (kg)</div>
                                    </div>
                                @endif

                                @if($assessment->height)
                                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($assessment->height, 1, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Altura (cm)</div>
                                    </div>
                                @endif

                                @if($assessment->bmi)
                                    <div class="text-center p-4 {{ $assessment->bmi < 18.5 || $assessment->bmi > 25 ? 'bg-orange-50 dark:bg-orange-900/20' : 'bg-green-50 dark:bg-green-900/20' }} rounded-lg">
                                        <div class="text-2xl font-bold {{ $assessment->bmi < 18.5 || $assessment->bmi > 25 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }}">
                                            {{ number_format($assessment->bmi, 1, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">IMC</div>
                                        @if($assessment->bmi_category)
                                            <div class="text-xs text-gray-500 mt-1">{{ $assessment->bmi_category }}</div>
                                        @endif
                                    </div>
                                @endif

                                @if($assessment->body_fat_percentage)
                                    <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($assessment->body_fat_percentage, 1, ',', '.') }}%</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">% Gordura</div>
                                    </div>
                                @endif

                                @if($assessment->muscle_mass_percentage)
                                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($assessment->muscle_mass_percentage, 1, ',', '.') }}%</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">% Músculo</div>
                                    </div>
                                @endif

                                @if($assessment->resting_heart_rate)
                                    <div class="text-center p-4 bg-pink-50 dark:bg-pink-900/20 rounded-lg">
                                        <div class="text-2xl font-bold text-pink-600 dark:text-pink-400">{{ $assessment->resting_heart_rate }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">FC Rest (bpm)</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Medidas Corporais -->
                        @if($assessment->chest_measurement || $assessment->waist_measurement || $assessment->hip_measurement || $assessment->arm_measurement || $assessment->thigh_measurement)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Circunferências (cm)</h3>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @if($assessment->chest_measurement)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Peito:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($assessment->chest_measurement, 1, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if($assessment->waist_measurement)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cintura:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($assessment->waist_measurement, 1, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if($assessment->hip_measurement)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Quadril:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($assessment->hip_measurement, 1, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if($assessment->arm_measurement)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Braço:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($assessment->arm_measurement, 1, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if($assessment->thigh_measurement)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Coxa:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($assessment->thigh_measurement, 1, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Saúde Cardiovascular -->
                        @if($assessment->resting_heart_rate || $assessment->max_heart_rate || $assessment->blood_pressure_systolic || $assessment->blood_pressure_diastolic)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Saúde Cardiovascular</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($assessment->resting_heart_rate)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">FC Repouso:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $assessment->resting_heart_rate }} bpm</span>
                                    </div>
                                @endif

                                @if($assessment->max_heart_rate)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">FC Máxima:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $assessment->max_heart_rate }} bpm</span>
                                    </div>
                                @endif

                                @if($assessment->blood_pressure_systolic && $assessment->blood_pressure_diastolic)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pressão Arterial:</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $assessment->blood_pressure_systolic }}/{{ $assessment->blood_pressure_diastolic }} mmHg</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Observações e Recomendações -->
                        @if($assessment->observations || $assessment->goals || $assessment->recommendations)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações do Professor</h3>

                            <div class="space-y-4">
                                @if($assessment->observations)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Observações:</h4>
                                        <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg whitespace-pre-wrap">{{ $assessment->observations }}</p>
                                    </div>
                                @endif

                                @if($assessment->goals)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Metas e Objetivos:</h4>
                                        <p class="text-gray-700 dark:text-gray-300 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border-l-4 border-blue-400 whitespace-pre-wrap">{{ $assessment->goals }}</p>
                                    </div>
                                @endif

                                @if($assessment->recommendations)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Recomendações:</h4>
                                        <p class="text-gray-700 dark:text-gray-300 bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border-l-4 border-green-400 whitespace-pre-wrap">{{ $assessment->recommendations }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>


<x-app-layout>
    <x-header>
        <x-slot:title>Avaliação Física - {{ $assessment->student->name }}</x-slot:title>
    </x-header>

    <div class="mt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informações do Aluno -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Aluno</h3>

                    <div class="space-y-4">
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300 text-sm">Nome:</strong>
                            <p class="text-gray-900 dark:text-white">{{ $assessment->student->name }}</p>
                        </div>

                        <div>
                            <strong class="text-gray-700 dark:text-gray-300 text-sm">Avaliado em:</strong>
                            <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('d/m/Y \à\s H:i') }}</p>
                        </div>

                        <div>
                            <strong class="text-gray-700 dark:text-gray-300 text-sm">Avaliador:</strong>
                            <p class="text-gray-900 dark:text-white">{{ $assessment->user->name ?? 'Sistema' }}</p>
                        </div>

                        <div>
                            <strong class="text-gray-700 dark:text-gray-300 text-sm">Estabelecimento:</strong>
                            <p class="text-gray-900 dark:text-white">{{ $assessment->establishment->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados da Avaliação -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <!-- Indicadores Principais -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Indicadores Principais</h3>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if($assessment->weight)
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($assessment->weight, 1, ',', '.') }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Peso (kg)</div>
                                </div>
                            @endif

                            @if($assessment->bmi)
                                <div class="text-center">
                                    <div class="text-2xl font-bold {{ $assessment->bmi < 18.5 || $assessment->bmi > 25 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }}">
                                        {{ number_format($assessment->bmi, 1, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">IMC</div>
                                    <div class="text-xs text-gray-500">{{ $assessment->bmi_category }}</div>
                                </div>
                            @endif

                            @if($assessment->body_fat_percentage)
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($assessment->body_fat_percentage, 1, ',', '.') }}%</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">% Gordura</div>
                                </div>
                            @endif

                            @if($assessment->resting_heart_rate)
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-pink-600 dark:text-pink-400">{{ $assessment->resting_heart_rate }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">FC Rest (bpm)</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Medidas Corporais -->
                    @if($assessment->chest_measurement || $assessment->waist_measurement || $assessment->hip_measurement || $assessment->arm_measurement || $assessment->thigh_measurement)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Circunferências (cm)</h3>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @if($assessment->chest_measurement)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Peito:</span>
                                    <span class="font-bold">{{ number_format($assessment->chest_measurement, 1, ',', '.') }}</span>
                                </div>
                            @endif

                            @if($assessment->waist_measurement)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cintura:</span>
                                    <span class="font-bold">{{ number_format($assessment->waist_measurement, 1, ',', '.') }}</span>
                                </div>
                            @endif

                            @if($assessment->hip_measurement)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Quadril:</span>
                                    <span class="font-bold">{{ number_format($assessment->hip_measurement, 1, ',', '.') }}</span>
                                </div>
                            @endif

                            @if($assessment->arm_measurement)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Braço:</span>
                                    <span class="font-bold">{{ number_format($assessment->arm_measurement, 1, ',', '.') }}</span>
                                </div>
                            @endif

                            @if($assessment->thigh_measurement)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Coxa:</span>
                                    <span class="font-bold">{{ number_format($assessment->thigh_measurement, 1, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Saúde Cardiovascular -->
                    @if($assessment->resting_heart_rate || $assessment->max_heart_rate || $assessment->blood_pressure_systolic || $assessment->blood_pressure_diastolic)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Saúde Cardiovascular</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($assessment->resting_heart_rate)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">FC Repouso:</span>
                                    <span class="font-bold">{{ $assessment->resting_heart_rate }} bpm</span>
                                </div>
                            @endif

                            @if($assessment->max_heart_rate)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">FC Máxima:</span>
                                    <span class="font-bold">{{ $assessment->max_heart_rate }} bpm</span>
                                </div>
                            @endif

                            @if($assessment->blood_pressure_systolic && $assessment->blood_pressure_diastolic)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pressão Arterial:</span>
                                    <span class="font-bold">{{ $assessment->blood_pressure_systolic }}/{{ $assessment->blood_pressure_diastolic }} mmHg</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Observações e Recomendações -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações do Professor</h3>

                        <div class="space-y-4">
                            @if($assessment->observations)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Observações:</h4>
                                    <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-3 rounded">{{ $assessment->observations }}</p>
                                </div>
                            @endif

                            @if($assessment->goals)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Metas e Objetivos:</h4>
                                    <p class="text-gray-700 dark:text-gray-300 bg-blue-50 dark:bg-blue-900/20 p-3 rounded border-l-4 border-blue-400">{{ $assessment->goals }}</p>
                                </div>
                            @endif

                            @if($assessment->recommendations)
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Recomendações:</h4>
                                    <p class="text-gray-700 dark:text-gray-300 bg-green-50 dark:bg-green-900/20 p-3 rounded border-l-4 border-green-400">{{ $assessment->recommendations }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('admin.physical_assessments.edit', $assessment->id) }}"
               class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Editar Avaliação
            </a>
            <a href="{{ route('admin.physical_assessments.index') }}"
               class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                Voltar à Lista
            </a>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-header>
        <x-slot:title>Avaliações Físicas</x-slot:title>
    </x-header>

    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-pink-600 via-rose-600 to-red-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Editar Avaliação Física - {{ $assessment->student->name }}</h1>
                        <p class="mt-1 text-pink-100">Atualize as informações da avaliação física</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('admin.physical_assessments.update', $assessment->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Informações Básicas -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Básicas</h3>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="student_id" :value="__('Aluno')" />
                            <select id="student_id" name="student_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Selecione um aluno</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}"
                                            {{ $assessment->student_id == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error name="student_id" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="assessment_date" :value="__('Data da Avaliação')" />
                            <input id="assessment_date" name="assessment_date" type="datetime-local"
                                   value="{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('Y-m-d\TH:i') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="assessment_date" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Medidas Básicas -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Medidas Básicas</h3>

                    <div class="grid gap-6 md:grid-cols-4">
                        <div>
                            <x-input-label for="weight" :value="__('Peso (kg)')" />
                            <input id="weight" name="weight" type="number" step="0.1" value="{{ number_format($assessment->weight ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="weight" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="height" :value="__('Altura (cm)')" />
                            <input id="height" name="height" type="number" step="0.1" value="{{ number_format($assessment->height ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="height" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="body_fat_percentage" :value="__('% Gordura')" />
                            <input id="body_fat_percentage" name="body_fat_percentage" type="number" step="0.1" min="0" max="100" value="{{ number_format($assessment->body_fat_percentage ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="body_fat_percentage" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="muscle_mass_percentage" :value="__('% Massa Muscular')" />
                            <input id="muscle_mass_percentage" name="muscle_mass_percentage" type="number" step="0.1" min="0" max="100" value="{{ number_format($assessment->muscle_mass_percentage ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="muscle_mass_percentage" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Circunferências -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Circunferências</h3>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div>
                            <x-input-label for="chest_measurement" :value="__('Peito (cm)')" />
                            <input id="chest_measurement" name="chest_measurement" type="number" step="0.1" value="{{ number_format($assessment->chest_measurement ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <x-input-label for="waist_measurement" :value="__('Cintura (cm)')" />
                            <input id="waist_measurement" name="waist_measurement" type="number" step="0.1" value="{{ number_format($assessment->waist_measurement ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <x-input-label for="hip_measurement" :value="__('Quadril (cm)')" />
                            <input id="hip_measurement" name="hip_measurement" type="number" step="0.1" value="{{ number_format($assessment->hip_measurement ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <x-input-label for="arm_measurement" :value="__('Braço (cm)')" />
                            <input id="arm_measurement" name="arm_measurement" type="number" step="0.1" value="{{ number_format($assessment->arm_measurement ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <x-input-label for="thigh_measurement" :value="__('Coxa (cm)')" />
                            <input id="thigh_measurement" name="thigh_measurement" type="number" step="0.1" value="{{ number_format($assessment->thigh_measurement ?? 0, 1, '.', '') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Saúde Cardiovascular -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Saúde Cardiovascular</h3>

                    <div class="grid gap-6 md:grid-cols-4">
                        <div>
                            <x-input-label for="resting_heart_rate" :value="__('FC Repouso (bpm)')" />
                            <input id="resting_heart_rate" name="resting_heart_rate" type="number" min="30" max="200" value="{{ $assessment->resting_heart_rate ?? '' }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <x-input-label for="max_heart_rate" :value="__('FC Máxima (bpm)')" />
                            <input id="max_heart_rate" name="max_heart_rate" type="number" min="100" max="250" value="{{ $assessment->max_heart_rate ?? '' }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <x-input-label for="blood_pressure_systolic" :value="__('Pressão Sistólica')" />
                            <input id="blood_pressure_systolic" name="blood_pressure_systolic" type="number" min="70" max="250" value="{{ $assessment->blood_pressure_systolic ?? '' }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <x-input-label for="blood_pressure_diastolic" :value="__('Pressão Diastólica')" />
                            <input id="blood_pressure_diastolic" name="blood_pressure_diastolic" type="number" min="40" max="150" value="{{ $assessment->blood_pressure_diastolic ?? '' }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Observações e Recomendações -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Observações do Professor</h3>

                    <div>
                        <x-input-label for="observations" :value="__('Observações')" />
                        <textarea id="observations" name="observations" rows="4"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $assessment->observations ?? '' }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="goals" :value="__('Metas e Objetivos')" />
                        <textarea id="goals" name="goals" rows="3"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $assessment->goals ?? '' }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="recommendations" :value="__('Recomendações')" />
                        <textarea id="recommendations" name="recommendations" rows="3"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $assessment->recommendations ?? '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.physical_assessments.show', $assessment->id) }}"
                       class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 mr-3">Cancelar</a>
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Atualizar Avaliação
                    </button>
                </div>
            </form>
            </div>
        </div>
    </section>
</x-app-layout>

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
                        <h1 class="text-3xl font-bold text-white">{{ $selectedStudent ? 'Avaliação Física - ' . $selectedStudent->name : 'Nova Avaliação Física' }}</h1>
                        <p class="mt-1 text-pink-100">Preencha os dados para realizar uma nova avaliação física</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                     role="alert">
                    <span class="font-medium">Sucesso!</span> {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.physical_assessments.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Informações Básicas -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Básicas</h3>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="student_id" :value="__('Aluno')" />
                            <select id="student_id" name="student_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    {{ $selectedStudent ? 'disabled' : '' }}>
                                <option value="">Selecione um aluno</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}"
                                            {{ ($selectedStudent && $selectedStudent->id == $student->id) || old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($selectedStudent)
                                <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                            @endif
                            <x-input-error name="student_id" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="assessment_date" :value="__('Data da Avaliação')" />
                            <input id="assessment_date" name="assessment_date" type="datetime-local" value="{{ old('assessment_date', now()->format('Y-m-d\TH:i')) }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="assessment_date" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- MEDIDAS BÁSICAS -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">MEDIDAS BÁSICAS</h3>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div>
                            <x-input-label for="weight" :value="__('Peso (kg)')" />
                            <input id="weight" name="weight" type="number" step="0.1" value="{{ old('weight') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="weight" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="height" :value="__('Altura (cm)')" />
                            <input id="height" name="height" type="number" step="0.1" value="{{ old('height') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="height" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="blood_pressure" :value="__('Pressão Arterial (mmHg)')" />
                            <div class="flex gap-2">
                                <input id="blood_pressure_systolic" name="blood_pressure_systolic" type="number" min="70" max="250" value="{{ old('blood_pressure_systolic') }}" placeholder="Sistólica"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <span class="self-center text-gray-500">/</span>
                                <input id="blood_pressure_diastolic" name="blood_pressure_diastolic" type="number" min="40" max="150" value="{{ old('blood_pressure_diastolic') }}" placeholder="Diastólica"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                            <x-input-error name="blood_pressure_systolic" class="mt-2" />
                            <x-input-error name="blood_pressure_diastolic" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- BIOIMPEDÂNCIA -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">BIOIMPEDÂNCIA</h3>

                    <div class="grid gap-6 md:grid-cols-5">
                        <div>
                            <x-input-label for="body_fat_percentage" :value="__('% Gordura')" />
                            <input id="body_fat_percentage" name="body_fat_percentage" type="number" step="0.1" min="0" max="100" value="{{ old('body_fat_percentage') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="body_fat_percentage" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="muscle_mass_percentage" :value="__('% Massa Magra')" />
                            <input id="muscle_mass_percentage" name="muscle_mass_percentage" type="number" step="0.1" min="0" max="100" value="{{ old('muscle_mass_percentage') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="muscle_mass_percentage" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="bmi" :value="__('IMC')" />
                            <input id="bmi" name="bmi" type="number" step="0.1" min="0" max="100" value="{{ old('bmi') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="bmi" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="body_age" :value="__('Idade Corporal (anos)')" />
                            <input id="body_age" name="body_age" type="number" min="0" max="150" value="{{ old('body_age') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="body_age" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="metabolic_rate" :value="__('Taxa Metabólica (kcal)')" />
                            <input id="metabolic_rate" name="metabolic_rate" type="number" min="0" value="{{ old('metabolic_rate') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="metabolic_rate" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- CIRCUNFERÊNCIAS -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">CIRCUNFERÊNCIAS (cm)</h3>

                    <div class="grid gap-6 md:grid-cols-4">
                        <div>
                            <x-input-label for="shoulder_measurement" :value="__('Ombro')" />
                            <input id="shoulder_measurement" name="shoulder_measurement" type="number" step="0.1" value="{{ old('shoulder_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="shoulder_measurement" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="chest_measurement" :value="__('Tórax')" />
                            <input id="chest_measurement" name="chest_measurement" type="number" step="0.1" value="{{ old('chest_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="chest_measurement" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="arm_measurement" :value="__('Braço')" />
                            <input id="arm_measurement" name="arm_measurement" type="number" step="0.1" value="{{ old('arm_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="arm_measurement" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="forearm_measurement" :value="__('Antebraço')" />
                            <input id="forearm_measurement" name="forearm_measurement" type="number" step="0.1" value="{{ old('forearm_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="forearm_measurement" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="waist_measurement" :value="__('Cintura')" />
                            <input id="waist_measurement" name="waist_measurement" type="number" step="0.1" value="{{ old('waist_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="waist_measurement" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="hip_measurement" :value="__('Quadril')" />
                            <input id="hip_measurement" name="hip_measurement" type="number" step="0.1" value="{{ old('hip_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="hip_measurement" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="thigh_measurement" :value="__('Coxa')" />
                            <input id="thigh_measurement" name="thigh_measurement" type="number" step="0.1" value="{{ old('thigh_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="thigh_measurement" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="leg_measurement" :value="__('Perna')" />
                            <input id="leg_measurement" name="leg_measurement" type="number" step="0.1" value="{{ old('leg_measurement') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="leg_measurement" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- TESTES CARDIOVASCULARES -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">TESTES CARDIOVASCULARES</h3>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div>
                            <x-input-label for="resting_heart_rate" :value="__('FC Repouso (bpm)')" />
                            <input id="resting_heart_rate" name="resting_heart_rate" type="number" min="30" max="200" value="{{ old('resting_heart_rate') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="resting_heart_rate" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="max_heart_rate" :value="__('FC Máxima (bpm)')" />
                            <input id="max_heart_rate" name="max_heart_rate" type="number" min="100" max="250" value="{{ old('max_heart_rate') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="max_heart_rate" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="post_exercise_heart_rate" :value="__('FC Pós Exercício (bpm)')" />
                            <input id="post_exercise_heart_rate" name="post_exercise_heart_rate" type="number" min="60" max="250" value="{{ old('post_exercise_heart_rate') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-input-error name="post_exercise_heart_rate" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- AVALIAÇÃO POSTURAL -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">AVALIAÇÃO POSTURAL</h3>

                    <div>
                        <x-input-label for="postural_photos" :value="__('Fotos da Avaliação Postural')" />
                        <input id="postural_photos" name="postural_photos[]" type="file" multiple accept="image/*"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Você pode selecionar múltiplas fotos. Formatos aceitos: JPG, PNG, GIF</p>
                        <x-input-error name="postural_photos" class="mt-2" />
                    </div>

                    <div id="photo-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>

                <!-- Observações e Recomendações -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Observações do Professor</h3>

                    <div>
                        <x-input-label for="observations" :value="__('Observações')" />
                        <textarea id="observations" name="observations" rows="4"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  placeholder="Digite observações sobre o estado atual do aluno...">{{ old('observations') }}</textarea>
                        <x-input-error name="observations" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="goals" :value="__('Metas e Objetivos')" />
                        <textarea id="goals" name="goals" rows="3"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  placeholder="Defina objetivos específicos para o próximo período...">{{ old('goals') }}</textarea>
                        <x-input-error name="goals" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="recommendations" :value="__('Recomendações')" />
                        <textarea id="recommendations" name="recommendations" rows="3"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  placeholder="Orientações sobre treino, alimentação, descanso...">{{ old('recommendations') }}</textarea>
                        <x-input-error name="recommendations" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.physical_assessments.index') }}"
                       class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 mr-3">Cancelar</a>
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Salvar Avaliação
                    </button>
                </div>
            </form>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Preview de fotos
        document.getElementById('postural_photos').addEventListener('change', function(e) {
            const preview = document.getElementById('photo-preview');
            preview.innerHTML = '';
            
            if (this.files) {
                Array.from(this.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-300">
                                <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 remove-photo">×</button>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Remover foto do preview (não remove do input, apenas visual)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-photo')) {
                e.target.closest('.relative').remove();
            }
        });
    </script>
    @endpush
</x-app-layout>

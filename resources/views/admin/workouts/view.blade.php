<x-app-layout>
    <x-header>
        <x-slot:title>Treinos</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 mx-full">
            <h2 class="text-4xl font-extrabold dark:text-white">
                <small class="font-semibold text-gray-500 dark:text-gray-400">{{ $workout->establishment->name }}</small>
            </h2>

            <div class="py-8 mx-auto w-full">
                <div class="mx-auto">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="user_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Instrutor</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $workout->user->name }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="student_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Aluno</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $workout->student->name }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="exercise_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Exercício</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $workout->exercise->name }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="order"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ordem</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $workout->order }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="sets"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Séries</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $workout->sets }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="repetitions"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Repetições</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $workout->repetitions }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="rest_time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempo de descanso</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $workout->rest_time }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="active"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <span class="text-gray-900 dark:text-white">
                                @if ($workout->active == 1)
                                    {{ 'Ativo' }}
                                @elseif ($workout->active == 0)
                                    {{ 'Inativo' }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>  
            
            <div class="mt-4 flex justify-end">
                <a href="{{ route('admin.workouts.index') }}"
                    class="ml-4 mt-2 text-gray-500 hover:text-gray-600">Voltar</a>
            </div>
        </div>
    </section>
</x-app-layout>

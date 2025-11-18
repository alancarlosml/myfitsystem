<x-app-layout>
    <x-header>
        <x-slot:title>Log de Treino - Detalhes</x-slot:title>
    </x-header>

    <div class="mt-12">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Aluno:</strong>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $workoutLog->student->name }}</p>
                </div>

                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Treino:</strong>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $workoutLog->workout->name }}</p>
                </div>

                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Data:</strong>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($workoutLog->date)->format('d/m/Y') }}</p>
                </div>

                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Data de Criação:</strong>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($workoutLog->created_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <a href="{{ route('admin.workout_logs.edit', $workoutLog->id) }}"
                   class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 mr-3">
                    Editar
                </a>
                <a href="{{ route('admin.workout_logs.index') }}"
                   class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                    Voltar à Lista
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

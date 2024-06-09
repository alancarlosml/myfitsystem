<x-app-layout>
    <x-header>
        <x-slot:title>Exercícios</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 mx-full">
            <h2 class="text-4xl font-extrabold dark:text-white">
                <small class="font-semibold text-gray-500 dark:text-gray-400">{{ $exercise->establishment->name }}</small>
            </h2>

            <div class="py-8 mx-auto w-full">
                <div class="mx-auto">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $exercise->name }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $exercise->description }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="exercise_picture"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagem do Exercício</label>
                            @if ($exercise->exercise_picture)
                                <img src="{{ asset('storage/' . $exercise->exercise_picture) }}" alt="Imagem do Exercício" class="w-32 h-32 object-cover">
                            @else
                                <span class="text-gray-900 dark:text-white">Nenhuma imagem disponível</span>
                            @endif
                        </div>
                        <div class="sm:col-span-2">
                            <label for="active"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <span class="text-gray-900 dark:text-white">
                                @if ($exercise->active == 1)
                                    {{ 'Ativo' }}
                                @elseif ($exercise->active == 0)
                                    {{ 'Inativo' }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>  
            
            <div class="mt-4 flex justify-end">
                <a href="{{ route('admin.exercises.index') }}"
                    class="ml-4 mt-2 text-gray-500 hover:text-gray-600">Voltar</a>
            </div>
        </div>
    </section>
</x-app-layout>

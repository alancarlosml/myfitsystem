<x-app-layout>
    <x-header>
        <x-slot:title>Categorias</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 mx-full">
            <h2 class="text-4xl font-extrabold dark:text-white">
                <small class="font-semibold text-gray-500 dark:text-gray-400">{{$category->establishment->name}}</small>
            </h2>

            <div class="py-8 mx-auto w-full">
                <div class="mx-auto">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $category->description }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="active"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stataus</label>
                            <span class="text-gray-900 dark:text-white">
                                @if ($category->active == 1)
                                    {{ 'Ativo' }}
                                @elseif ($category->active == 0)
                                    {{ 'Inativo' }}
                                @endif
                        </div>
                    </div>
                </div>
            </div>  
            
            <div class="mt-4 flex justify-end">
                <a href="{{ route('admin.categories.index') }}"
                    class="ml-4 mt-2 text-gray-500 hover:text-gray-600">Voltar</a>
            </div>
        </div>
    </section>
</x-app-layout>

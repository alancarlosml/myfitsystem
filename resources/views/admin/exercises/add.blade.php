<x-app-layout>
    <x-header>
        <x-slot:title>Exercícios</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-4xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Adicionar Exercício</h2>

            <x-alert-error />

            <form action="{{ route('admin.exercises.store') }}" method="POST">
                @include('admin.exercises.partials.form')
            </form>
        </div>
    </section>
</x-app-layout>

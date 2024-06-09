<x-app-layout>
    <x-header>
        <x-slot:title>Treinos</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-4xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Adicionar Treino</h2>

            <x-alert-error />

            <form action="{{ route('admin.workouts.store') }}" method="POST">
                @include('admin.workouts.partials.form')
            </form>
        </div>
    </section>
</x-app-layout>

<x-app-layout>
    <x-header>
        <x-slot:title>Categorias</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-4xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Editar Categoria</h2>

            <x-alert-error />   

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @method('put')
                @include('admin.categories.partials.form')
            </form>
        </div>
    </section>
    @push('head')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    @endpush
    @push('footer')
        <script>

        </script>
    @endpush

</x-app-layout>

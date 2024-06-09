<x-app-layout>
    <x-header>
        <x-slot:title>Usuários</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-4xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Editar Usuário</h2>

            <x-alert-error />

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @method('put')
                @include('admin.users.partials.form')
            </form>
        </div>
    </section>
    @push('head')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    @endpush
    @push('footer')
        <script>
            const datepickerEl = document.getElementById('birthdate');
            new Datepicker(datepickerEl, {
                // options
            });
        </script>
    @endpush

</x-app-layout>

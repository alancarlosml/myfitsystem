<x-app-layout>
    <x-header>
        <x-slot:title>Treinos</x-slot:title>
    </x-header>

    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Editar Treino</h1>
                        <p class="mt-1 text-blue-100">Atualize as informações do treino</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-error />

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('admin.workouts.update', $workout->id) }}" method="POST">
                    @method('put')
                    @include('admin.workouts.partials.form')
                </form>
            </div>
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

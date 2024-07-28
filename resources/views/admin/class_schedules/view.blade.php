<x-app-layout>
    <x-header>
        <x-slot:title>Agendamento de aulas</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 mx-full">
            <h2 class="text-4xl font-extrabold dark:text-white">
                <small class="font-semibold text-gray-500 dark:text-gray-400">{{$class_schedule->establishment->name}}</small>
            </h2>

            <div class="py-8 mx-auto w-full">
                <div class="mx-auto">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="modality_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Modalidade</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $class_schedule->modality->name }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $class_schedule->description }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="class_date"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data aula</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($class_schedule->class_date)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="start_time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Início aula</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($class_schedule->start_time)->format('H:i') }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="end_time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fim aula</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($class_schedule->end_time)->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="overflow-x-auto" x-data="{ class_bookings: {{ @json_encode($class_schedule->class_bookings) }}, selectAll: false }">
                @include('admin.class_bookings.partials.table', ['class_bookings' => $class_schedule->class_bookings])
            </div>
            
            <div class="mt-4 flex justify-end">
                <a href="{{ route('admin.class_schedules.index') }}"
                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Voltar</a>
            </div>
        </div>
    </section>
</x-app-layout>

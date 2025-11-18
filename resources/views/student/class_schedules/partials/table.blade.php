<table class="w-full text-md text-left text-gray-500 dark:text-gray-400">
    <thead class="text-base text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="py-3 px-6">
                Modalidade
            </th>
            <th scope="col" class="py-3 px-6">
                Descrição
            </th>
            <th scope="col" class="py-3 px-6">
                Data
            </th>
            <th scope="col" class="py-3 px-6">
                Início
            </th>
            <th scope="col" class="py-3 px-6">
                Fim
            </th>
            <th scope="col" class="py-3 px-6">
                Ações
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($class_schedules as $class_schedule)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $class_schedule->modality->name }}
                </td>
                <td scope="row"
                    class="py-4 px-6">
                    {{ $class_schedule->description }}
                </td>
                <td scope="row"
                    class="py-4 px-5">
                    {{ \Carbon\Carbon::parse($class_schedule->class_date)->format('d/m/Y') }}
                </td>
                <td scope="row"
                    class="py-4 px-6">
                    {{ \Carbon\Carbon::parse($class_schedule->start_time)->format('H:i') }}
                </td>
                <td scope="row"
                    class="py-4 px-6">
                    {{ \Carbon\Carbon::parse($class_schedule->end_time)->format('H:i') }}
                </td>
                <td class="px-6 py-4">
                    @if(isset($class_schedule->booking_id) && $class_schedule->booking_id)
                        <span class="font-medium text-blue-600 dark:text-blue-500">Inscrito</span>
                    @else
                        <button @click="bookClass($event)" class="book-btn font-medium text-green-600 dark:text-green-500 hover:underline bg-transparent border-none p-0" data-schedule-id="{{ $class_schedule->id }}">Reservar</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="w-full text-md text-left text-gray-500 dark:text-gray-400">
    <thead class="text-base text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="py-3 px-6">
                Exercício
            </th>
            <th scope="col" class="py-3 px-6">
                Séries
            </th>
            <th scope="col" class="py-3 px-6">
                Repetições
            </th>
            <th scope="col" class="py-3 px-6">
                Tempo de Descanso
            </th>
            <th scope="col" class="py-3 px-6">
                Observações
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($workouts as $workout)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $workout->exercise->name }}
                </td>
                <td scope="row"
                    class="py-4 px-6">
                    {{ $workout->sets }}
                </td>
                <td scope="row"
                    class="py-4 px-6">
                    {{ $workout->repetitions }}
                </td>
                <td scope="row"
                    class="py-4 px-6">
                    {{ $workout->rest_time }}s
                </td>
                <td scope="row"
                    class="py-4 px-6">
                    {{ $workout->notes }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

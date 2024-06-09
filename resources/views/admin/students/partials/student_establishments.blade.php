<table class="w-full text-md text-left text-gray-500 dark:text-gray-400">
    <thead class="text-base text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="p-4">
                <div class="flex items-center">
                    <input id="checkbox-all-search" type="checkbox"
                           class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                </div>
            </th>
            <th scope="col" class="py-3 px-6">Nome do estabelecimento</th>
            <th scope="col" class="py-3 px-6">Tipo</th>
            <th scope="col" class="py-3 px-6">CNPJ</th>
            <th scope="col" class="py-3 px-6">Telefone</th>
            <th scope="col" class="py-3 px-6">Status</th>
            <th scope="col" class="py-3 px-6">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($establishments as $establishment)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="p-4 w-4">
                    <div class="flex items-center">
                        <input type="checkbox"
                               class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="sr-only">checkbox</label>
                    </div>
                </td>
                <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $establishment->name }}</th>
                <td class="py-4 px-6">
                    @if ($establishment->type == 'academia')
                        Academia
                    @elseif ($establishment->type == 'crossfit')
                        Crossfit
                    @elseif ($establishment->type == 'personal_trainer')
                        Personal trainer
                    @endif
                </td>
                <td class="py-4 px-6">{{ $establishment->cnpj }}</td>
                <td class="py-4 px-6">{{ $establishment->phone }}</td>
                <td class="py-4 px-6">
                    <div class="flex items-center">
                        <div
                             class="inline-block w-4 h-4 mr-2 rounded-full {{ $establishment->active ? 'bg-green-700' : 'bg-red-700' }}">
                        </div>
                        <span>{{ $establishment->active ? 'Ativo' : 'Inativo' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 flex items-center">
                    <a href="{{ route('admin.students.contracts', ['student' => $student->id, 'establishment' => $establishment->id]) }}"
                       class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Contratos</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

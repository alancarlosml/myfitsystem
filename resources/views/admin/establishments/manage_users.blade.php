<x-app-layout>
    <x-header>
        <x-slot:title>Estabelecimentos</x-slot:title>
    </x-header>

    <div class="bg-gradient-to-r from-amber-600 via-yellow-600 to-orange-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $establishment->name }}</h1>
                        <p class="mt-1 text-amber-100">GestÃ£o de colaboradores do estabelecimento</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-xs text-amber-200">Tipo</p>
                        <p class="text-sm font-medium text-white">
                            @if ($establishment->type == 'crossfit')
                                Crossfit
                            @elseif ($establishment->type == 'academia')
                                Academia
                            @elseif ($establishment->type == 'personal_trainer')
                                Personal Trainer
                            @endif
                        </p>
                    </div>
                    @if ($establishment->active == 1)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-500/20 text-green-100 border border-green-300/30">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            Ativo
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-500/20 text-red-100 border border-red-300/30">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            Inativo
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-establishment-tabs :establishment="$establishment" />

            <div class="mt-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Colaboradores</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie os usuÃ¡rios vinculados e seus respectivos papÃ©is.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('admin.establishments.create') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg shadow-sm transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Adicionar novo
                        </a>
                        <a href="#"
                           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                            </svg>
                            Deletar selecionados
                        </a>
                        <div class="relative">
                            <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400 dark:text-amber-200 dark:bg-amber-600/20 dark:hover:bg-amber-600/30 dark:border-amber-500"
                                    type="button">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                Exportar
                                <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                            <div id="actionsDropdown"
                                 class="hidden absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-100 divide-y divide-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-700 z-20">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="actionsDropdownButton">
                                    <li>
                                        <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">Excel</a>
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-200 dark:hover:text-white">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                        <form class="max-w-md">
                            <label for="simple-search" class="sr-only">Pesquisar</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search"
                                       class="block w-full pl-10 pr-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
                                       placeholder="Buscar colaborador" required>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                            <thead class="text-xs font-semibold uppercase tracking-wide bg-gray-50 text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-all-search" type="checkbox" x-model="selectAll"
                                                   class="w-4 h-4 text-amber-600 bg-gray-100 rounded border-gray-300 focus:ring-amber-500 dark:focus:ring-amber-500 dark:ring-offset-gray-900 focus:ring-2 dark:bg-gray-800 dark:border-gray-600">
                                            <label for="checkbox-all-search" class="sr-only">Selecionar todos</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="py-3 px-6">#</th>
                                    <th scope="col" class="py-3 px-6">Nome</th>
                                    <th scope="col" class="py-3 px-6">CPF</th>
                                    <th scope="col" class="py-3 px-6">Email</th>
                                    <th scope="col" class="py-3 px-6">Papel</th>
                                    <th scope="col" class="py-3 px-6">Status</th>
                                    <th scope="col" class="py-3 px-6">AÃ§Ãµes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="p-4 w-4">
                                            <div class="flex items-center">
                                                <input type="checkbox"
                                                       class="w-4 h-4 text-amber-600 bg-gray-100 rounded border-gray-300 focus:ring-amber-500 dark:focus:ring-amber-500 dark:ring-offset-gray-900 focus:ring-2 dark:bg-gray-800 dark:border-gray-600">
                                                <label class="sr-only">Selecionar colaborador</label>
                                            </div>
                                        </td>
                                        <th scope="row" class="py-4 px-6 font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $user->id }}
                                        </th>
                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </td>
                                        <td class="py-4 px-6">{{ $user->cpf }}</td>
                                        <td class="py-4 px-6">{{ $user->email }}</td>
                                        <td class="py-4 px-6">
                                            {{ ucfirst($user->roles->where('pivot.establishment_id', $establishment->id)->first()->name ?? 'N/A') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <span class="inline-flex w-2.5 h-2.5 mr-2 rounded-full {{ $user->active == 1 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                                @if ($user->active == 1)
                                                    {{ 'Ativo' }}
                                                @else
                                                    {{ 'Inativo' }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <a href="#" id="defaultModalEditButton-{{ $user->id }}"
                                                   data-modal-target="defaultModalEdit-{{ $user->id }}"
                                                   data-modal-toggle="defaultModalEdit-{{ $user->id }}"
                                                   class="text-amber-600 hover:text-amber-700 font-medium">Editar</a>
                                                <a href="#"
                                                   onclick="showModal(event, {{ $user->id }}, {{ $establishment->id }})"
                                                   data-modal-target="popup-modal"
                                                   data-modal-toggle="popup-modal"
                                                   class="text-red-600 hover:text-red-700 font-medium">Excluir</a>
                                                <script>
                                                    function showModal(event, userId, establishmentId) {
                                                        event.preventDefault();
                                                        document.getElementById('popup-modal').classList.remove('hidden');
                                                        document.getElementById('confirm-button').onclick = function() {
                                                            unlinkEstablishment(userId, establishmentId);
                                                        };
                                                    }

                                                    function unlinkEstablishment(userId, establishmentId) {
                                                        var url = `/usuarios/${userId}/desvincular/${establishmentId}`;
                                                        window.location.href = url;
                                                    }
                                                </script>
                                                <div id="defaultModalEdit-{{ $user->id }}" tabindex="-1" aria-hidden="true"
                                                     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                                        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                                            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-700">
                                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                                    Editar papel do usuÃ¡rio
                                                                </h3>
                                                                <button type="button"
                                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                                        data-modal-toggle="defaultModalEdit-{{ $user->id }}">
                                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <span class="sr-only">Fechar modal</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('admin.users.establishments.update', $user) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                                    <input type="hidden" name="establishment_id" value="{{ $establishment->id }}">
                                                                    <div>
                                                                        <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Papel</label>
                                                                        <select id="role_id" name="role_id"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200">
                                                                            <option selected>Selecione</option>
                                                                            @foreach ($roles as $role)
                                                                                <option value="{{ $role->id }}"
                                                                                        @if (old('role_id', $user->roles->where('pivot.establishment_id', $establishment->id)->first()->id ?? null) == $role->id) selected @endif>
                                                                                    {{ ucfirst($role->name) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="sm:col-span-2">
                                                                        <label class="inline-flex items-center cursor-pointer">
                                                                            <input type="checkbox" value="1" class="sr-only peer" name="active"
                                                                                   {{ old('active', $user->active ?? false) ? 'checked' : '' }}>
                                                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-600 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500">
                                                                            </div>
                                                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-amber-200 dark:focus:ring-amber-600">
                                                                    Salvar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('admin.establishments.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar para lista
                </a>
            </div>
        </div>
    </section>
</x-app-layout>

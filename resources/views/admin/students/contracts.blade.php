<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <span class="text-3xl font-bold text-white">{{ substr($student->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Contratos</h1>
                        <p class="mt-1 text-purple-100">{{ $student->name }} - {{ $establishment->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-xs text-purple-200">Total de contratos</p>
                        <p class="text-2xl font-bold text-white">{{ $contracts->count() }}</p>
                    </div>
                    @if ($student->active == 1)
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
                    <a href="{{ route('admin.students.view', $student->id) }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-success />

            <!-- Barra de Busca e Ações -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="w-full md:w-1/2">
                        <label for="simple-search" class="sr-only">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                     fill="currentColor" viewbox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                   placeholder="Buscar contratos...">
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="#" id="defaultModalButton" data-modal-target="defaultModal"
                           data-modal-toggle="defaultModal"
                           class="inline-flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewbox="0 0 20 20">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Adicionar novo
                        </a>
                        <button type="button"
                                class="inline-flex items-center justify-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 011 1v3H9V4a1 1 0 011-1zM6 7h12v13a1 1 0 01-1 1H7a1 1 0 01-1-1V7z" />
                            </svg>
                            Deletar selecionados
                        </button>
                        <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors"
                                type="button">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Exportar
                            <svg class="ml-2 w-4 h-4" fill="currentColor" viewbox="0 0 20 20">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                        <div id="actionsDropdown"
                             class="hidden z-10 w-44 bg-white rounded-lg divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="actionsDropdownButton">
                                <li>
                                    <a href="#"
                                       class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Excel</a>
                                </li>
                            </ul>
                            <div class="py-1">
                                <a href="#"
                                   class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal de adicionar contrato --}}
            <div id="defaultModal" tabindex="-1" aria-hidden="true"
                 class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                    <!-- Modal content -->
                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                        <!-- Modal header -->
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Adicionar novo contrato
                            </h3>
                            <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-toggle="defaultModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <form action="{{ route('admin.students.contracts.store', [$student, $establishment]) }}"
                              method="POST">
                            @csrf
                            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                <div>
                                    <label for="service_name"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Serviço</label>
                                    <select id="service_name" name="service_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                        <option selected="">Selecione</option>
                                        <option value="semanal">Semanal</option>
                                        <option value="mensal">Mensal</option>
                                        <option value="trimestral">Trimestral</option>
                                        <option value="semestral">Semestral</option>
                                        <option value="anual">Anual</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="amount"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                                    <input type="text" name="amount" id="amount"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="payment_date"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data
                                        de pagamento</label>
                                    <input type="text" name="payment_date" id="payment_date"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="payment_type"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Forma
                                        de pagamento</label>
                                    <select id="payment_type" name="payment_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                        <option selected="">Selecione</option>
                                        <option value="credito">Crédito</option>
                                        <option value="debito">Débito</option>
                                        <option value="pix">Pix</option>
                                        <option value="boleto">Boleto</option>
                                        <option value="dinheiro">Dinheiro</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="start_date"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Início</label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="end_date"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fim</label>
                                    <input type="text" name="end_date" id="end_date"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" value="1" class="sr-only peer"
                                               name="active" {{ old('active') ? 'checked' : '' }}>
                                        <div
                                             class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600">
                                        </div>
                                        <span
                                              class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
                                    </label>
                                </div>
                            </div>
                            <button type="submit"
                                    class="flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800 transition-colors">
                                Adicionar novo
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabela -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto" x-data="{ contracts: {{ @json_encode($contracts) }}, selectAll: false }">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-all-search" type="checkbox" x-model="selectAll"
                                               class="w-4 h-4 text-indigo-600 bg-gray-100 rounded border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                    </div>
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    #
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Serviço
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Total
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Data pagamento
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Forma de pagamento
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Início contrato
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Fim contrato
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Status
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contracts as $contract)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="p-4 w-4">
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   class="w-4 h-4 text-indigo-600 bg-gray-100 rounded border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label class="sr-only">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $contract->id }}
                                    </td>
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        @if ($contract->service_name == 'semanal')
                                            Semanal
                                        @elseif ($contract->service_name == 'mensal')
                                            Mensal
                                        @elseif ($contract->service_name == 'trimestral')
                                            Trimestral
                                        @elseif ($contract->service_name == 'semestral')
                                            Semestral
                                        @elseif ($contract->service_name == 'anual')
                                            Anual
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        R$ {{ number_format($contract->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ \Carbon\Carbon::parse($contract->payment_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($contract->payment_type == 'debito')
                                            Débito
                                        @elseif ($contract->payment_type == 'credito')
                                            Crédito
                                        @elseif ($contract->payment_type == 'boleto')
                                            Boleto
                                        @elseif ($contract->payment_type == 'pix')
                                            Pix
                                        @elseif ($contract->payment_type == 'dinheiro')
                                            Dinheiro
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($contract->active == 1)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <a href="#"
                                               class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Editar</a>
                                            <a href="#" data-modal-target="popup-modal-{{ $contract->id }}"
                                               data-modal-toggle="popup-modal-{{ $contract->id }}"
                                               class="font-medium text-red-600 dark:text-red-500 hover:underline">Excluir</a>

                                            <!-- Modal de confirmação de exclusão -->
                                            <div id="popup-modal-{{ $contract->id }}" tabindex="-1"
                                                 class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                <div class="relative p-4 w-full max-w-md max-h-full">
                                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                        <button type="button"
                                                                class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                                data-modal-hide="popup-modal-{{ $contract->id }}">
                                                            <svg class="w-3 h-3" aria-hidden="true"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none" viewBox="0 0 14 14">
                                                                <path stroke="currentColor"
                                                                      stroke-linecap="round"
                                                                      stroke-linejoin="round" stroke-width="2"
                                                                      d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                            </svg>
                                                            <span class="sr-only">Close modal</span>
                                                        </button>
                                                        <div class="p-4 md:p-5 text-center">
                                                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                                                 aria-hidden="true"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none" viewBox="0 0 20 20">
                                                                <path stroke="currentColor"
                                                                      stroke-linecap="round"
                                                                      stroke-linejoin="round" stroke-width="2"
                                                                      d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                            </svg>
                                                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                                                Você tem certeza que deseja excluir este contrato?
                                                            </h3>
                                                            <button data-modal-hide="popup-modal-{{ $contract->id }}"
                                                                    type="button"
                                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                                Sim, tenho certeza
                                                            </button>
                                                            <button data-modal-hide="popup-modal-{{ $contract->id }}"
                                                                    type="button"
                                                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-indigo-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                                Não, cancelar
                                                            </button>
                                                        </div>
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
            </div>
        </div>
    </section>

</x-app-layout>

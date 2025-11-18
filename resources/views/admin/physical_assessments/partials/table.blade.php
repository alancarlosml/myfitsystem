<table class="w-full text-md text-left text-gray-500 dark:text-gray-400">
    <thead class="text-base text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="py-3 px-6">
                Aluno
            </th>
            <th scope="col" class="py-3 px-6">
                Peso
            </th>
            <th scope="col" class="py-3 px-6">
                IMC
            </th>
            <th scope="col" class="py-3 px-6">
                % Gordura
            </th>
            <th scope="col" class="py-3 px-6">
                Data Avaliação
            </th>
            <th scope="col" class="py-3 px-6">
                Professor
            </th>
            <th scope="col" class="py-3 px-6">
                Ações
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($assessments as $assessment)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div>
                        <div class="text-base font-semibold">{{ $assessment->student->name }}</div>
                        @if($assessment->bmi)
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $assessment->bmi_category }}</div>
                        @endif
                    </div>
                </th>
                <td class="py-4 px-6">
                    @if($assessment->weight)
                        {{ number_format($assessment->weight, 1, ',', '.') }} kg
                    @else
                        -
                    @endif
                </td>
                <td class="py-4 px-6">
                    @if($assessment->bmi)
                        <span class="font-medium {{ $assessment->bmi < 18.5 || $assessment->bmi > 25 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }}">
                            {{ number_format($assessment->bmi, 1, ',', '.') }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td class="py-4 px-6">
                    @if($assessment->body_fat_percentage)
                        {{ number_format($assessment->body_fat_percentage, 1, ',', '.') }}%
                    @else
                        -
                    @endif
                </td>
                <td class="py-4 px-6">
                    <div class="text-sm">
                        <div class="font-medium">{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('d/m/Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('H:i') }}</div>
                    </div>
                </td>
                <td class="py-4 px-6">
                    @if($assessment->user)
                        {{ $assessment->user->name }}
                    @else
                        Sistema
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.physical_assessments.show', $assessment->id) }}" title="Ver"
                           class="p-2 text-green-600 dark:text-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.physical_assessments.edit', $assessment->id) }}" title="Editar"
                           class="p-2 text-blue-600 dark:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <a href="#" onclick="confirmDelete({{ $assessment->id }})" title="Excluir"
                           class="p-2 text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </a>
                    </div>

                    <div id="popup-modal-{{ $assessment->id }}" tabindex="-1"
                         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <button type="button"
                                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-hide="popup-modal-{{ $assessment->id }}">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Fechar</span>
                                </button>
                                <div class="p-4 md:p-5 text-center">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                         aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Tem certeza que deseja excluir esta avaliação física?</h3>
                                    <button onclick="deleteAssessment({{ $assessment->id }})"
                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                        Sim, excluir
                                    </button>
                                    <button data-modal-hide="popup-modal-{{ $assessment->id }}" type="button"
                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function confirmDelete(id) {
                            document.getElementById('popup-modal-' + id).classList.remove('hidden');
                            document.getElementById('popup-modal-' + id).classList.add('flex');
                        }

                        function deleteAssessment(id) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/gestao/avaliacoes-fisicas/${id}`;
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    </script>
                </td>
            </tr>
        @endforeach

        @if($assessments->isEmpty())
            <tr>
                <td colspan="7" class="py-6 px-6 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-6 4h6m2 3H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2v-3l4-3v8a2 2 0 01-2 2H8a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="text-lg font-medium">Nenhuma avaliação encontrada</p>
                        <p class="text-sm">Comece criando a primeira avaliação física.</p>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>

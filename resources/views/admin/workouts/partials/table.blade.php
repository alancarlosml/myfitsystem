<table class="w-full text-md text-left text-gray-500 dark:text-gray-400">
    <thead class="text-base text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="p-4">
                <div class="flex items-center">
                    <input id="checkbox-all-search" type="checkbox" x-model="selectAll"
                           class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                </div>
            </th>
            <th scope="col" class="py-3 px-6">
                Estabelecimento
            </th>
            <th scope="col" class="py-3 px-6">
                Instrutor
            </th>
            <th scope="col" class="py-3 px-6">
                Aluno
            </th>
            <th scope="col" class="py-3 px-6">
                treino
            </th>
            <th scope="col" class="py-3 px-6">
                Séries
            </th>
            <th scope="col" class="py-3 px-6">
                Repetições
            </th>
            <th scope="col" class="py-3 px-6">
                Tempo de descanso
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
        <template x-for="workout in workouts" :key="workout.id">
            <tr
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                <td class="p-4 w-4">
                    <div class="flex items-center">
                    <input type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            :id="`checkbox-table-search-` + workout.id"
                            :checked="selectAll"> <label :for="`checkbox-table-search-` + workout.id" class="sr-only">checkbox</label>
                    </div>
                </td>
                <td scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    x-text="workout.establishment.name">
                </td>
                <td scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    x-text="workout.user.name">
                </td>
                <td scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    x-text="workout.student.name">
                </td>
                <td scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    x-text="workout.exercise.name">
                </td>
                <td scope="row"
                    class="py-4 px-6"
                    x-text="workout.sets">
                </td>
                <td scope="row"
                    class="py-4 px-6"
                    x-text="workout.repetitions">
                </td>
                <td scope="row"
                    class="py-4 px-6"
                    x-text="workout.rest_time">
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center">
                        <div class="inline-block w-4 h-4 mr-2 rounded-full" x-bind:class="workout.active ? 'bg-green-700' : 'bg-red-700'"></div>
                        <span x-text="workout.active ? 'Ativo' : 'Inativo'"></span>
                    </div>
                </td>
                <td class="px-6 py-4 flex items-center">
                    <a href="#" x-on:click="viewWorkout($event, workout.id)" class="font-medium mr-3 text-green-600 dark:text-green-500 hover:underline">Detalhes</a>
                    <div x-data="{ selectedworkoutId: null }">
                        <script>
                            function viewWorkout(event, workoutId) {
                                event.preventDefault();
                                var editUrl = `{{ url('/treinos') }}/${workoutId}/detalhes`;
                                window.location.href = editUrl;
                            }
                        </script>
                    </div>
                    <a href="#" x-on:click="editWorkout($event, workout.id)"
                    class="font-medium mr-3 text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                    <div x-data="{ selectedworkoutId: null }">
                        <script>
                            function editWorkout(event, workoutId) {
                                event.preventDefault();
                                var editUrl = `{{ url('/treinos') }}/${workoutId}/editar`;
                                window.location.href = editUrl;
                            }
                        </script>
                    </div>
                    <a href="#" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="font-medium text-red-600 dark:text-red-500 hover:underline">Excluir</a>
                    
                    <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="p-4 md:p-5 text-center">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Você tem certeza que deseja excluir essa treino?</h3>
                                    <button data-modal-hide="popup-modal"  x-on:click="deleteWorkout($event, workout.id)" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                        Sim, tenho certeza
                                    </button>
                                    <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Não, cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <script>
                        function deleteWorkout(event, workoutId) {
                            event.preventDefault();
            
                            fetch(`/treinos/${workoutId}/excluir`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (response.ok) {
                                        window.location.href = '{{ route('admin.workouts.index') }}';
                                    } else {
                                        alert('Ocorreu um erro ao excluir o treino.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Erro:', error);
                                    alert('Ocorreu um erro ao excluir o treino.');
                                });
                        }
                    </script>
                </td>
            </tr>
        </template>

    </tbody>
</table>
<?php
    
    use Carbon\Carbon;

    $role = null;
    $user = null;
    if (Auth::guard('user')->check()) {
        $role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
        $user = Auth::user();
    } 

    // Definindo o mês e ano que deseja exibir
    // $year = Carbon::today()->format('Y');
    // $month = Carbon::today()->format('m');

    // $today = Carbon::today()->format('Y-m-d');

    // // Obter o primeiro dia do mês
    // $startOfMonth = Carbon::createFromDate($year, $month, 1);

    // // Obter o dia da semana do primeiro dia do mês (0=domingo, 1=segunda, ...)
    // $firstDayOfWeek = $startOfMonth->dayOfWeek;

    // // Obter o número de dias no mês
    // $daysInMonth = $startOfMonth->daysInMonth;

    // // Calcular o número de dias do mês anterior a serem exibidos
    // $daysFromPrevMonth = $firstDayOfWeek ? $firstDayOfWeek : 7;

    // // Obter o primeiro dia a ser exibido (dias do mês anterior)
    // $startDate = $startOfMonth->copy()->subDays($daysFromPrevMonth);

    // // Array para armazenar as datas do calendário
    // $calendar = [];

    // // Preencher o calendário com 35 dias (5 semanas)
    // for ($i = 0; $i < 35; $i++) {
    //     $date = $startDate->copy()->addDays($i);
    //     $formattedDate = $date->format('Y-m-d');

    //     // Inicializar o array para o dia
    //     if (!isset($calendar[$formattedDate])) {
    //         $calendar[$formattedDate] = [
    //             'date' => $date,
    //             'events' => [],
    //             'is_today' => $formattedDate === $today,
    //         ];
    //     }

    //     // Adicionar eventos se existirem para essa data
    //     foreach ($classSchedules as $schedule) {
    //         $eventDate = Carbon::parse($schedule->class_date)->format('Y-m-d');
    //         if ($formattedDate == $eventDate) {
    //             $calendar[$formattedDate]['events'][] = $schedule;
    //         }
    //     }
    // }

?>
<div id="calendar">
<x-app-layout>
    <x-header>
        <x-slot:title>Aulas</x-slot:title>
    </x-header>

    <x-alert-success />

    <section class="mt-12">
        <div class="mx-auto w-full">
            <!-- component -->
            <div class="items-center justify-center py-4 px-4">
                @if ($role && in_array($role->name, ['superuser', 'admin']))
                <div class="flex justify-end mb-3">
                    <a href="{{ route('admin.class_schedules.create') }}" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Adicionar novo
                    </a>
                </div>
                @endif
                <div class="w-full border border-gray-400 dark:border-gray-700 rounded-lg p-4">
                    <div class="md:p-8 p-5 dark:bg-gray-800 bg-white">
                        <div class="px-4 mb-4 flex items-center justify-between">
                            <span tabindex="0" class="focus:outline-none font-bold dark:text-gray-100 text-gray-800 text-2xl">
                                {{ucfirst($monthName)}} {{ \Carbon\Carbon::createFromDate($year, $month)->format('Y') }}
                            </span>
                            <div class="flex items-center">
                                <button aria-label="calendar backward" class="focus:text-gray-400 hover:text-gray-400 text-gray-800 dark:text-gray-100" onclick="changeMonth({{ $month - 1 }}, {{ $year }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="15 6 9 12 15 18" />
                                    </svg>
                                </button>
                                <button aria-label="calendar forward" class="focus:text-gray-400 hover:text-gray-400 ml-3 text-gray-800 dark:text-gray-100" onclick="changeMonth({{ $month + 1 }}, {{ $year }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler  icon-tabler-chevron-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="9 6 15 12 9 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-12 overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th><p class="text-base font-medium text-center">Seg</p></th>
                                        <th><p class="text-base font-medium text-center">Ter</p></th>
                                        <th><p class="text-base font-medium text-center">Qua</p></th>
                                        <th><p class="text-base font-medium text-center">Qui</p></th>
                                        <th><p class="text-base font-medium text-center">Sex</p></th>
                                        <th><p class="text-base font-medium text-center">Sab</p></th>
                                        <th><p class="text-base font-medium text-center">Dom</p></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_chunk($calendar, 7) as $week)
                                        <tr>
                                            @foreach($week as $dayData)
                                                <td class="lg:pt-4 sm:pt-0">
                                                    @if ($dayData['date']->month == $month)
                                                        @if (!empty($dayData['events']))
                                                            <div class="flex items-center justify-center w-full rounded-full cursor-pointer">
                                                                <a role="link" tabindex="0" data-date="{{ $dayData['date']->format('Y-m-d') }}" data-today="{{ $dayData['is_today'] ? 'true' : 'false' }}" class="text-base w-8 h-8 flex items-center justify-center font-medium text-blue-700 @if ($dayData['is_today']) bg-blue-100 rounded-full @endif">{{ $dayData['date']->day }}</a>
                                                            </div>
                                                        @else
                                                            <div class="px-4 py-4 flex w-full justify-center">
                                                                <p class="text-base dark:text-gray-100 text-gray-800 @if ($dayData['is_today']) bg-blue-100 rounded-full w-8 h-8 flex items-center justify-center @endif" data-today="{{ $dayData['is_today'] ? 'true' : 'false' }}">{{ $dayData['date']->day }}</p>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if (!empty($dayData['events']))
                                                            <div class="flex items-center justify-center w-full rounded-full cursor-pointer">
                                                                <a role="link" tabindex="0" data-date="{{ $dayData['date']->format('Y-m-d') }}" class="text-base w-8 h-8 flex items-center justify-center dark:text-gray-200 text-gray-500 font-bold">{{ $dayData['date']->day }}</a>
                                                            </div>
                                                        @else
                                                            <div class="px-4 py-4 flex w-full justify-center">
                                                                <p class="text-base dark:text-gray-100 text-gray-400">{{ $dayData['date']->day }}</p>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="md:py-8 py-5 md:px-16 px-5 dark:bg-gray-700 bg-gray-50 rounded-b">
                        <div class="px-4" id="event-container">
                            <p class="text-center text-gray-500">Nenhum evento para este dia.</p>
                            {{-- @if (!empty($events))
                                @foreach($events as $event)
                                    <div class="border-b pb-4 border-gray-400 border-dashed mt-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <p class="text-xs font-light leading-3 text-gray-500 dark:text-gray-300">{{ $event->start_time->format('H:i A') }}</p>
                                                <a tabindex="0" class="focus:outline-none text-lg font-medium leading-5 text-gray-800 dark:text-gray-100 mt-2">{{ $event->description }}</a>
                                            </div>
                                            @if ($role && in_array($role->name, ['superuser', 'admin']))
                                            <div class="flex items-center">
                                                <a href="{{ route('admin.class_schedules.create') }}" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                                    </svg>
                                                    Editar
                                                </a>
                                                <a href="{{ route('admin.class_schedules.create') }}" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                                    </svg>
                                                    Excluir
                                                </a>
                                            </div>
                                            @else
                                            <div class="flex items-center">
                                                <a href="{{ route('admin.class_schedules.create') }}" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                                    </svg>
                                                    Inscrever
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('head')
    @endpush

    @push('footer')
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script>
            function changeMonth(month, year) {
                if (month < 1) {
                    month = 12;
                    year--;
                } else if (month > 12) {
                    month = 1;
                    year++;
                }
                
                $.ajax({
                    url: "{{ route('admin.class_bookings.index') }}",
                    type: 'GET',
                    data: {
                        month: month,
                        year: year
                    },
                    success: function(response) {
                        $('#calendar').html(response);
                    }
                });
            }
            $(document).ready(function() {
                $('a[data-date]').click(function(e) {
                    e.preventDefault();
                    
                    var date = $(this).data('date');
                    var $this = $(this); 

                    $.ajax({
                        url: '{{ route("admin.class_bookings.get_events") }}',
                        method: 'GET',
                        data: { date: date },
                        success: function(response) {
                            // Limpa o container de eventos
                            $('#event-container').empty();

                            $('a[data-date]').removeClass('hover:bg-blue-300 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:bg-blue-400 bg-blue-300 ring-2 ring-offset-2 ring-blue-500 bg-blue-400');
                
                            // Adiciona a classe ao elemento clicado
                            $this.addClass('hover:bg-blue-300 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:bg-blue-400 bg-blue-300 ring-2 ring-offset-2 ring-blue-500 bg-blue-400 rounded-full');

                            // Verifica se há eventos na resposta
                            if (response.events && response.events.length > 0) {
                                // Itera sobre cada evento
                                $.each(response.events, function(index, event) {
                                    // Formata a hora de início e descrição do evento
                                    var startTime = event.start_time.split(':').slice(0, 2).join(':');
                                    var endTime = event.end_time.split(':').slice(0, 2).join(':');
                                    var description = event.description || 'Sem descrição';
                                    var user_name = event.user_name || 'Sem instrutor';

                                    // Cria o HTML para o evento
                                    var eventHtml = `
                                        <div class="border-b pb-4 border-gray-400 border-dashed mt-3">
                                            <div class="flex items-center justify-between">
                                                <div class="w-full">
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">${startTime} - ${endTime}</span>
                                                    <p tabindex="0" class="focus:outline-none text-lg font-medium leading-5 text-gray-800 dark:text-gray-100 mt-2">${description}</p>
                                                    <p class="text-xs font-light leading-3 text-gray-500 dark:text-gray-300 mt-2">${user_name}</p>
                                                </div>
                                                <div class="flex items-center">
                                                    <a href="{{ route('admin.class_schedules.create') }}" class="flex items-center justify-center text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                                        </svg>
                                                        Inscrever
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                                    // Adiciona o HTML ao container
                                    $('#event-container').append(eventHtml);
                                });
                            } else {
                                // Caso não haja eventos
                                $('#event-container').html('<p class="text-center text-gray-500">Nenhum evento para este dia.</p>');
                            }
                        },
                        error: function() {
                            alert('Erro ao carregar eventos.');
                        }
                    });
                });

                // Simula um clique no dia atual ao carregar a página
                $('a[data-today="true"]').trigger('click');
            });
        </script>
    @endpush

</x-app-layout>
</div>
<div class="w-full border-2 border-gray-400 dark:border-gray-700 rounded-lg p-4">
    <div class="md:p-8 p-5 dark:bg-gray-800 bg-white">
        <div class="px-4 mb-4 flex items-center justify-between">
            <span tabindex="0" class="focus:outline-none font-bold dark:text-gray-100 text-gray-800 text-2xl">
                {{ \Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}
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
                                                <p class="text-base dark:text-gray-100 text-gray-800">{{ $dayData['date']->day }}</p>
                                            </div>
                                        @endif
                                    @else
                                        @if (!empty($dayData['events']))
                                            <div class="flex items-center justify-center w-full rounded-full cursor-pointer">
                                                <a role="link" tabindex="0" data-date="{{ $dayData['date']->format('Y-m-d') }}" class="text-base w-8 h-8 flex items-center justify-center dark:text-gray-200 text-gray-400 font-bold">{{ $dayData['date']->day }}</a>
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
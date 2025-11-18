<div class="md:p-8 p-5 dark:bg-gray-800 bg-white" id="calendar-container">
    <div class="px-4 mb-4 flex items-center justify-between">
        <span tabindex="0" class="focus:outline-none font-bold dark:text-gray-100 text-gray-800 text-2xl">
            {{ucfirst($monthName)}} {{ \Carbon\Carbon::createFromDate($year, $month)->format('Y') }}
        </span>
        <div class="flex items-center">
            <button onclick="changeMonth('prev')" aria-label="calendar backward" class="focus:text-gray-400 hover:text-gray-400 text-gray-800 dark:text-gray-100">
                {{-- <div class="w-6 h-6" style="background: black;"></div> --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <polyline points="15 6 9 12 15 18" />
                </svg>
            </button>
            <button onclick="changeMonth('next')" aria-label="calendar forward" class="focus:text-gray-400 hover:text-gray-400 ml-3 text-gray-800 dark:text-gray-100">
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
                                @if (!empty($dayData['events']))
                                    <div class="flex items-center justify-center w-full rounded-full cursor-pointer relative">
                                        <a @click="loadEvents($event, '{{ $dayData['date']->format('Y-m-d') }}')" role="link" tabindex="0" class="text-base w-8 h-8 flex items-center justify-center font-medium @if($dayData['has_booked_event']) text-green-700 @else text-blue-700 @endif @if ($dayData['is_today']) bg-blue-100 rounded-full @endif cursor-pointer">{{ $dayData['date']->day }}</a>
                                        @if($dayData['has_booked_event'])
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                                        @endif
                                    </div>
                                @else
                                    <div class="px-4 py-4 flex w-full justify-center">
                                        <p class="text-base dark:text-gray-100 text-gray-800 @if ($dayData['is_today']) bg-blue-100 rounded-full w-8 h-8 flex items-center justify-center @endif">{{ $dayData['date']->day }}</p>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

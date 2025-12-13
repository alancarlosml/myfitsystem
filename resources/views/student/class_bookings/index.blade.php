@php
    // Usar layout mobile apenas se for dispositivo móvel
    $layout = (!empty($isMobile) && $isMobile === true) ? 'layouts.student-mobile' : 'layouts.app';
@endphp
@extends($layout)

@section('content')
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Olá, {{ auth()->guard('student')->user()->name }}! 📅</h1>
                    <p class="mt-1 text-blue-100">Agende suas aulas e acompanhe seu cronograma semanal</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-sm font-medium text-white">{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('MMMM YYYY') }}</p>
                        <p class="text-xs text-blue-100">Semana atual</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters e Navegação -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <!-- Navegação de Mês/Ano -->
                <div class="flex items-center space-x-4">
                    <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 p-3 rounded-lg transition-colors duration-200" onclick="changeDate(-1)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 text-lg font-bold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors duration-200" onclick="setTodayView()">
                            Hoje
                        </button>
                        <span class="text-gray-500 dark:text-gray-400">•</span>
                        <button class="px-4 py-2 text-lg font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors duration-200" onclick="setWeekView()">
                            Semana
                        </button>
                        <button class="px-4 py-2 text-lg font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors duration-200" onclick="setMonthView()">
                            Mês
                        </button>
                    </div>

                    <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 p-3 rounded-lg transition-colors duration-200" onclick="changeDate(1)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <!-- Filtros e Busca -->
                <!-- Filtros e Busca -->
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <div class="relative w-full sm:w-auto">
                        <input type="text" placeholder="Buscar aulas..."
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-300">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Filtro por Modalidade -->
                    <select class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Todas as aulas</option>
                        <option value="musculacao">Musculação</option>
                        <option value="yoga">Yoga</option>
                        <option value="crossfit">Crossfit</option>
                        <option value="artes-marciais">Artes Marciais</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Métricas Rápidas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-emerald-100 text-xs md:text-sm font-medium">Reservadas</p>
                        <h3 class="text-xl md:text-2xl font-bold text-white">{{ $bookingsStatistics['classesThisMonth'] ?? 0 }}</h3>
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-blue-100 text-xs md:text-sm font-medium">Horas/Semana</p>
                        <h3 class="text-xl md:text-2xl font-bold text-white">{{ $bookingsStatistics['weekHours'] ?? 0 }}h</h3>
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-violet-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-purple-100 text-xs md:text-sm font-medium">Próxima</p>
                        @if($bookingsStatistics['nextClass'] ?? null)
                            <p class="text-sm md:text-lg font-bold text-white">{{ \Carbon\Carbon::parse($bookingsStatistics['nextClass']->classSchedule->class_date)->locale('pt_BR')->isoFormat('dd/MM') }} {{ \Carbon\Carbon::parse($bookingsStatistics['nextClass']->classSchedule->start_time)->format('H:i') }}</p>
                        @else
                            <p class="text-sm md:text-lg font-bold text-white">Sem aulas</p>
                        @endif
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-3 md:p-4 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <p class="text-orange-100 text-xs md:text-sm font-medium">Dias Para</p>
                        <h3 class="text-xl md:text-2xl font-bold text-white">{{ $bookingsStatistics['daysUntil'] ?? '-' }}</h3>
                    </div>
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white/80 self-end md:self-center hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m15-5a6 6 0 11-6-6 6 6 0 016 6z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Layout do Calendário -->
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">

            <!-- Calendário Principal -->
            <div class="xl:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Calendário Semanal
                        </h2>
                    </div>

                    <!-- Calendário (usando o componente existente) -->
                    <div class="p-6" id="calendar-wrapper">
                        @include('student.class_bookings.partials.calendar')
                    </div>
                </div>
            </div>

            <!-- Sidebar Lateral -->
            <div class="space-y-6">

                <!-- Minhas Reservas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Minhas Reservas
                        </h2>
                    </div>
                    <div class="p-4">
                        @forelse($studentBookings ?? [] as $booking)
                            @php
                                $classDate = \Carbon\Carbon::parse($booking->class_date)->locale('pt_BR');
                                $today = now()->locale('pt_BR');
                                $isToday = $classDate->isToday();
                                $isTomorrow = $classDate->isTomorrow();
                                $isFuture = $classDate->isAfter($today);

                                // Determine relative label and color
                                if ($isToday) {
                                    $dateLabel = 'Hoje';
                                    $labelColor = 'bg-green-100 text-green-800';
                                } elseif ($isTomorrow) {
                                    $dateLabel = 'Amanhã';
                                    $labelColor = 'bg-blue-100 text-blue-800';
                                } elseif ($isFuture) {
                                    $daysDiff = $classDate->diffInDays($today);
                                    $dateLabel = $daysDiff <= 3 ? 'Em ' . $daysDiff . ' dias' : $classDate->isoFormat('dd/MM');
                                    $labelColor = 'bg-purple-100 text-purple-800';
                                } else {
                                    $dateLabel = $classDate->isoFormat('dd/MM');
                                    $labelColor = 'bg-gray-100 text-gray-800';
                                }
                            @endphp

                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg mb-3 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $booking->classSchedule->modality->name ?? 'Aula' }}
                                    </h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $labelColor }}">
                                        {{ $dateLabel }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                                    ⏰ {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Sala: {{ $booking->classSchedule->class_room ?? 'Principal' }}
                                    @if($booking->classSchedule->user)
                                        • {{ $booking->classSchedule->user->name }}
                                    @endif
                                </p>
                                <div class="mt-3 flex justify-end">
                                    <button class="text-xs text-red-600 hover:text-red-700 font-medium" onclick="cancelBooking({{ $booking->id }})">
                                        Cancelar reserva
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    Você ainda não tem aulas reservadas
                                </p>
                                <span class="text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                    Agende sua primeira aula
                                </span>
                            </div>
                        @endforelse

                        <a href="{{ route('student.class_schedules.index') }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg transition-colors duration-200 font-medium mt-4 inline-block text-center">
                            📅 Agendar Nova Aula
                        </a>
                    </div>
                </div>

                <!-- Disciplinas Populares -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-red-600 p-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Mais Procuradas
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <!-- Disciplina 1 -->
                            <div class="flex items-center p-3 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg">
                                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">Musculação</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">8h disponíveis hoje</p>
                                </div>
                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>

                            <!-- Disciplina 2 -->
                            <div class="flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">Yoga</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">5h disponíveis hoje</p>
                                </div>
                                <div class="flex">
                                    ⭐⭐⭐⭐⭐
                                </div>
                            </div>

                            <!-- Disciplina 3 -->
                            <div class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">Crossfit</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">3h disponíveis hoje</p>
                                </div>
                                <div class="flex">
                                    ⭐⭐⭐⭐⭐
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentYear = {{ $year }};
        let currentMonth = {{ $month }};

        function changeMonth(direction) {
            if (event) {
                event.preventDefault();
            }
            
            if (direction === 'prev') {
                currentMonth--;
                if (currentMonth < 1) {
                    currentMonth = 12;
                    currentYear--;
                }
            } else if (direction === 'next') {
                currentMonth++;
                if (currentMonth > 12) {
                    currentMonth = 1;
                    currentYear++;
                }
            }

            // Mostrar loading
            const calendarContainer = document.getElementById('calendar-container');
            const calendarWrapper = document.getElementById('calendar-wrapper');
            
            if (calendarContainer) {
                calendarContainer.style.opacity = '0.5';
                calendarContainer.style.pointerEvents = 'none';
            }

            // Fazer requisição AJAX
            fetch('{{ route("student.class_bookings.index") }}?year=' + currentYear + '&month=' + currentMonth, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(response => response.text())
            .then(html => {
                // O controller retorna apenas o partial quando é AJAX
                // Atualizar o conteúdo do wrapper
                if (calendarWrapper) {
                    calendarWrapper.innerHTML = html;
                    
                    // Atualizar referência do container após atualizar o HTML
                    const newCalendarContainer = document.getElementById('calendar-container');
                    if (newCalendarContainer) {
                        newCalendarContainer.style.opacity = '1';
                        newCalendarContainer.style.pointerEvents = 'auto';
                    }
                } else {
                    // Se não encontrou o wrapper, recarregar a página inteira
                    window.location.href = '{{ route("student.class_bookings.index") }}?year=' + currentYear + '&month=' + currentMonth;
                }
            })
            .catch(error => {
                console.error('Erro ao carregar calendário:', error);
                if (calendarContainer) {
                    calendarContainer.style.opacity = '1';
                    calendarContainer.style.pointerEvents = 'auto';
                }
                alert('Erro ao carregar o calendário. Tente novamente.');
            });
        }

        function changeDate(direction) {
            // Redirecionar para mudança de mês
            changeMonth(direction === -1 ? 'prev' : 'next');
        }

        function setTodayView() {
            const now = new Date();
            currentYear = now.getFullYear();
            currentMonth = now.getMonth() + 1;
            
            // Se já estamos no mês atual, apenas recarregar
            if (currentYear === {{ $year }} && currentMonth === {{ $month }}) {
                location.reload();
            } else {
                // Carregar o mês atual
                const calendarContainer = document.getElementById('calendar-container');
                const calendarWrapper = document.getElementById('calendar-wrapper');
                
                if (calendarContainer) {
                    calendarContainer.style.opacity = '0.5';
                    calendarContainer.style.pointerEvents = 'none';
                }

                fetch('{{ route("student.class_bookings.index") }}?year=' + currentYear + '&month=' + currentMonth, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    if (calendarWrapper) {
                        calendarWrapper.innerHTML = html;
                        const newCalendarContainer = document.getElementById('calendar-container');
                        if (newCalendarContainer) {
                            newCalendarContainer.style.opacity = '1';
                            newCalendarContainer.style.pointerEvents = 'auto';
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar calendário:', error);
                    window.location.href = '{{ route("student.class_bookings.index") }}?year=' + currentYear + '&month=' + currentMonth;
                });
            }
        }

        function setWeekView() {
            console.log('Visualizar semana');
            // Implementar visualização de semana
        }

        function setMonthView() {
            console.log('Visualizar mês');
            // Já está na visualização de mês
        }

        async function cancelBooking(bookingId) {
            if (confirm('Tem certeza que deseja cancelar esta reserva?')) {
                try {
                    const response = await fetch(`/app/cancelar-reserva/${bookingId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Reserva cancelada com sucesso!');
                        location.reload(); // Recarrega a página para atualizar as informações
                    } else {
                        alert(result.message || 'Erro ao cancelar reserva');
                    }
                } catch (error) {
                    console.error('Erro ao cancelar reserva:', error);
                    alert('Erro ao cancelar reserva. Tente novamente.');
                }
            }
        }
    </script>
@endsection

@php
    // Usar layout mobile apenas se for dispositivo móvel
    $layout = (!empty($isMobile) && $isMobile === true) ? 'layouts.student-mobile' : 'layouts.app';
@endphp
@extends($layout)

@section('content')
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Olá, {{ auth()->guard('student')->user()->name }}! 🕐</h1>
                    <p class="mt-1 text-blue-100">Consulte horários disponíveis e faça suas reservas</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-sm font-medium text-white">{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, D/M') }}</p>
                        <p class="text-xs text-blue-100">Horários disponíveis</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <section x-data="bookingHandler" 
                 x-init="console.log('Alpine inicializado'); showModal = false; modalType = ''; modalTitle = ''; modalMessage = '';"
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filtros e Pesquisa -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Horários Disponíveis</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $class_schedules->count() }} aulas encontradas
                        </span>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Buscar aulas..."
                                   class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-300">
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Todas as modalidades</option>
                            <option value="musculacao">Musculação</option>
                            <option value="yoga">Yoga</option>
                            <option value="crossfit">Crossfit</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Cards das Modalidades -->
            @php
                $groupedSchedules = $class_schedules->groupBy(function($schedule) {
                    return $schedule->modality->name ?? 'Outros';
                });
            @endphp

            <div class="space-y-8">
                @foreach($groupedSchedules as $modalityName => $schedules)
                    <!-- Card da Modalidade -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <!-- Header da Modalidade -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                        @switch(strtolower($modalityName))
                                            @case('musculação')
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                                @break
                                            @case('yoga')
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                                @break
                                            @case('crossfit')
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                @break
                                            @default
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 100 4m0-4v2m0-6V4m0 0H9m3 0h3"/>
                                                </svg>
                                        @endswitch
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-white">{{ $modalityName }}</h2>
                                        <p class="text-blue-100 text-sm">{{ $schedules->count() }} aula{{ $schedules->count() > 1 ? 's' : '' }} disponível{{ $schedules->count() > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                <div class="hidden sm:block">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                                        Ver todas
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Grid de Aulas -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($schedules as $schedule)
                                    <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
                                        <!-- Data e Status -->
                                        <div class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-600 dark:to-gray-700 p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{ \Carbon\Carbon::parse($schedule->class_date)->locale('pt_BR')->isoFormat('dddd, D/MM') }}
                                                </div>
                                                @if(isset($schedule->booking_id) && $schedule->booking_id)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Reservado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Disponível
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Horário -->
                                            <div class="flex items-center space-x-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                            </div>
                                        </div>

                                        <!-- Conteúdo da Aula -->
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $schedule->description }}</h3>

                                            {{-- @if($schedule->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">{{ $schedule->description }}</p>
                                            @endif --}}

                                            <!-- Informações da Aula -->
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <span>Sala {{ $schedule->class_room ?? 'Principal' }}</span>
                                                </div>

                                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    <span>Prof. {{ $schedule->instructor ?? 'Equipe FitSystem' }}</span>
                                                </div>
                                            </div>

                                            <!-- Status e Ação -->
                                            <div class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                                                @if(isset($schedule->booking_id) && $schedule->booking_id)
                                                    <div class="flex items-center text-blue-600 dark:text-blue-400">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-sm font-medium">Inscrito</span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-green-600 dark:text-green-400">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="text-sm font-medium">Vaga disponível</span>
                                                    </div>
                                                @endif

                                                @if(isset($schedule->booking_id) && $schedule->booking_id)
                                                    <button class="bg-gray-500 hover:bg-gray-600 text-gray-200 dark:text-gray-300 hover:text-gray-300 dark:hover:text-gray-300 text-sm px-4 py-2 rounded-lg transition-colors duration-200 font-medium group-hover:scale-105">
                                                        Detalhes
                                                    </button>
                                                @else
                                                    <button @click="bookClass($event)"
                                                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition-colors duration-200 font-medium group-hover:scale-105"
                                                            data-schedule-id="{{ $schedule->id }}">
                                                        Reservar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Estado Vazio -->
                @if($class_schedules->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhum horário encontrado</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">Não há aulas programadas no momento. Volte em breve!</p>
                        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                            Voltar ao Painel
                        </a>
                    </div>
                @endif
            </div>

        <!-- Modal -->
        <div x-show="showModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="closeModal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" 
             style="display: none;">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 shadow-xl"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="flex items-center mb-4">
                    <div x-show="modalType === 'success'" 
                         x-transition
                         class="text-green-500 mr-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div x-show="modalType === 'error'" 
                         x-transition
                         class="text-red-500 mr-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 x-show="modalTitle" 
                        x-text="modalTitle" 
                        class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                </div>
                <p x-show="modalMessage" 
                   x-text="modalMessage" 
                   class="text-gray-600 dark:text-gray-300 mb-4"></p>
                <button @click="closeModal" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Fechar
                </button>
            </div>
        </div>
        </section>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookingHandler', () => ({
                showModal: false,
                modalType: '',
                modalTitle: '',
                modalMessage: '',
                csrfToken: '{{ csrf_token() }}',

                openModal(type, title, message) {
                    this.modalType = type;
                    this.modalTitle = title;
                    this.modalMessage = message;
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                    // Reset modal state when closing
                    setTimeout(() => {
                        this.modalType = '';
                        this.modalTitle = '';
                        this.modalMessage = '';
                    }, 300);
                },

                bookClass(event) {
                    console.log('bookClass chamado', event);
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Garantir que pegamos o botão corretamente
                    const button = event.currentTarget || event.target.closest('button');
                    console.log('Button:', button);
                    
                    const scheduleId = button ? button.getAttribute('data-schedule-id') : null;
                    console.log('Schedule ID:', scheduleId);

                    if (!scheduleId) {
                        console.error('Schedule ID não encontrado');
                        this.openModal('error', 'Erro!', 'Erro: ID da aula não encontrado.');
                        return;
                    }

                    // Desabilitar botão durante a requisição
                    const originalText = button.textContent;
                    button.disabled = true;
                    button.textContent = 'Reservando...';

                    fetch('{{ route("student.class_bookings.book") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            class_schedule_id: scheduleId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Erro na requisição');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            button.textContent = 'Inscrito';
                            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                            button.classList.add('bg-gray-500', 'hover:bg-gray-600');
                            button.disabled = true;
                            this.openModal('success', 'Sucesso!', data.message || 'Aula reservada com sucesso!');
                            // Recarregar a página após 1.5 segundos
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            button.disabled = false;
                            button.textContent = originalText;
                            this.openModal('error', 'Erro!', data.message || 'Erro ao fazer reserva.');
                        }
                    })
                    .catch(error => {
                        button.disabled = false;
                        button.textContent = originalText;
                        this.openModal('error', 'Erro!', error.message || 'Erro ao fazer reserva. Tente novamente.');
                        console.error('Error:', error);
                    });
                }
            }));
        });
    </script>

@endsection

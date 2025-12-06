<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 text-white">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Agendamento de Aulas</h1>
                    <p class="mt-1 text-teal-100">Gerencie todos os agendamentos de aulas do sistema</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-xs text-teal-200">Total de agendamentos</p>
                        <p class="text-2xl font-bold text-white">{{ $class_schedules->count() }}</p>
                    </div>
                    <a href="{{ route('admin.class_schedules.create') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewbox="0 0 20 20">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Novo agendamento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-success />

            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium">Total de Agendamentos</p>
                            <p class="text-3xl font-bold mt-1">{{ $class_schedules->count() }}</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-sm font-medium">Este Mês</p>
                            <p class="text-3xl font-bold mt-1">{{ $class_schedules->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                            <p class="text-xs text-teal-200 mt-1">novos agendamentos</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-cyan-100 text-sm font-medium">Próximos 7 Dias</p>
                            <p class="text-3xl font-bold mt-1">{{ $class_schedules->filter(function($schedule) { return \Carbon\Carbon::parse($schedule->class_date)->isFuture() && \Carbon\Carbon::parse($schedule->class_date)->diffInDays(now()) <= 7; })->count() }}</p>
                            <p class="text-xs text-cyan-200 mt-1">agendamentos</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de Busca e Filtros -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('admin.class_schedules.index') }}" id="filterForm" x-data="filterData" x-cloak>
                    <!-- Linha de Busca -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
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
                                <input type="text" name="search" id="simple-search" x-model="filters.search"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                                       placeholder="Buscar agendamentos por modalidade, instrutor ou data..."
                                       value="{{ request('search') }}"
                                       @keyup.enter.debounce.300ms="applyFilters()">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="toggleFilters()" 
                                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filtros Avançados
                                <svg class="w-4 h-4 ml-2 transition-transform" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <button type="button" @click="clearFilters()"
                                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Limpar
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Filtros Avançados (Expandível) -->
                    <div x-show="showFilters" x-transition 
                         class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Filtro por Data (De) -->
                            <div>
                                <label for="date_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Data a partir de
                                </label>
                                <input type="date" name="date_from" id="date_from" x-model="filters.date_from"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                                       value="{{ request('date_from') }}">
                            </div>

                            <!-- Filtro por Data (Até) -->
                            <div>
                                <label for="date_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Data até
                                </label>
                                <input type="date" name="date_to" id="date_to" x-model="filters.date_to"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                                       value="{{ request('date_to') }}">
                            </div>

                            <!-- Filtro por Data de Cadastro (De) -->
                            <div>
                                <label for="created_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Cadastrado a partir de
                                </label>
                                <input type="date" name="created_from" id="created_from" x-model="filters.created_from"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                                       value="{{ request('created_from') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Chips de Filtros Ativos -->
                    <div x-show="hasActiveFilters()" x-transition
                         class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtros ativos:</span>
                            
                            <template x-for="(value, key) in filters" :key="key">
                                <template x-if="value && key !== 'search'">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                        <span x-text="getFilterLabel(key, value)"></span>
                                        <button type="button" @click="removeFilter(key)" 
                                                class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-emerald-200 dark:hover:bg-emerald-700 transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </template>

                            <template x-if="filters.search">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                    Busca: "<span x-text="filters.search"></span>"
                                    <button type="button" @click="filters.search = ''; applyFilters()" 
                                            class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-teal-200 dark:hover:bg-teal-700 transition-colors">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <button type="button" @click="clearFilters()"
                                    class="ml-auto text-xs text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 font-medium">
                                Limpar todos
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabela -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto" x-data="{ class_schedules: {{ @json_encode($class_schedules) }}, selectAll: false }">
                    @include('admin.class_schedules.partials.table', ['class_schedules' => $class_schedules])
                </div>
            </div>
        </div>
    </section>

    @push('footer')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('filterData', () => ({
                showFilters: @json(!empty(request('date_from')) || !empty(request('date_to')) || !empty(request('created_from'))),
                filters: {
                    search: '{{ request('search', '') }}',
                    date_from: '{{ request('date_from', '') }}',
                    date_to: '{{ request('date_to', '') }}',
                    created_from: '{{ request('created_from', '') }}'
                },
                toggleFilters() {
                    this.showFilters = !this.showFilters;
                },
                hasActiveFilters() {
                    return this.filters.date_from || this.filters.date_to || this.filters.created_from || this.filters.search;
                },
                getFilterLabel(key, value) {
                    if (!value) return '';
                    
                    const labels = {
                        date_from: 'De: ' + new Date(value).toLocaleDateString('pt-BR'),
                        date_to: 'Até: ' + new Date(value).toLocaleDateString('pt-BR'),
                        created_from: 'Cadastrado a partir de: ' + new Date(value).toLocaleDateString('pt-BR')
                    };
                    
                    return labels[key] || key + ': ' + value;
                },
                removeFilter(key) {
                    this.filters[key] = '';
                    this.applyFilters();
                },
                clearFilters() {
                    window.location.href = '{{ route('admin.class_schedules.index') }}';
                },
                applyFilters() {
                    document.getElementById('filterForm').submit();
                }
            }))
        });
    </script>
    @endpush
</x-app-layout>

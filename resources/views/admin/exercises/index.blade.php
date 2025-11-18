<x-app-layout>

    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-orange-600 via-red-600 to-pink-600 text-white">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Exercícios</h1>
                    <p class="mt-1 text-orange-100">Gerencie todos os exercícios do sistema</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-xs text-orange-200">Total de exercícios</p>
                        <p class="text-2xl font-bold text-white">{{ $exercises->count() }}</p>
                    </div>
                    <a href="{{ route('admin.exercises.create') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewbox="0 0 20 20">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Novo exercício
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
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Total de Exercícios</p>
                            <p class="text-3xl font-bold mt-1">{{ $exercises->count() }}</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Exercícios Ativos</p>
                            <p class="text-3xl font-bold mt-1">{{ $exercises->where('active', 1)->count() }}</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Este Mês</p>
                            <p class="text-2xl font-bold mt-1">{{ $exercises->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                            <p class="text-xs text-red-200 mt-1">novos exercícios</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de Busca e Filtros -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('admin.exercises.index') }}" id="filterForm" x-data="filterData" x-cloak>
                    <!-- Linha de Busca -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div class="w-full md:w-1/2">
                            <label for="simple-search" class="sr-only">Buscar</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="simple-search" x-model="filters.search"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500"
                                       placeholder="Buscar exercícios por nome ou descrição..."
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
                                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Filtro por Status -->
                            <div>
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Status
                                </label>
                                <select name="status" id="status" x-model="filters.status"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500">
                                    <option value="">Todos</option>
                                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>

                            <!-- Filtro por Categoria -->
                            <div>
                                <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Categoria
                                </label>
                                <select name="category_id" id="category_id" x-model="filters.category_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500">
                                    <option value="">Todas</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por Estabelecimento (Superuser) -->
                            @if($establishments->count() > 0)
                                <div>
                                    <label for="establishment_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Estabelecimento
                                    </label>
                                    <select name="establishment_id" id="establishment_id" x-model="filters.establishment_id"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500">
                                        <option value="">Todos</option>
                                        @foreach($establishments as $establishment)
                                            <option value="{{ $establishment->id }}" {{ request('establishment_id') == $establishment->id ? 'selected' : '' }}>
                                                {{ $establishment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Filtro por Data de Cadastro (De) -->
                            <div>
                                <label for="created_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Cadastrado a partir de
                                </label>
                                <input type="date" name="created_from" id="created_from" x-model="filters.created_from"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500"
                                       value="{{ request('created_from') }}">
                            </div>

                            <!-- Filtro por Data de Cadastro (Até) -->
                            <div>
                                <label for="created_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Cadastrado até
                                </label>
                                <input type="date" name="created_to" id="created_to" x-model="filters.created_to"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500"
                                       value="{{ request('created_to') }}">
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                        <span x-text="getFilterLabel(key, value)"></span>
                                        <button type="button" @click="removeFilter(key)" 
                                                class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-orange-200 dark:hover:bg-orange-700 transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </template>

                            <template x-if="filters.search">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Busca: "<span x-text="filters.search"></span>"
                                    <button type="button" @click="filters.search = ''; applyFilters()" 
                                            class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-red-200 dark:hover:bg-red-700 transition-colors">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <button type="button" @click="clearFilters()"
                                    class="ml-auto text-xs text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300 font-medium">
                                Limpar todos
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Ações em Massa -->
                <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 011 1v3H9V4a1 1 0 011-1zM6 7h12v13a1 1 0 01-1 1H7a1 1 0 01-1-1V7z"/>
                            </svg>
                            Deletar selecionados
                        </button>
                        <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors"
                                type="button">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            Exportar
                            <svg class="ml-2 w-4 h-4" fill="currentColor" viewbox="0 0 20 20">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                        <div id="actionsDropdown"
                             class="hidden z-10 w-44 bg-white rounded-lg divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="actionsDropdownButton">
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        Excel
                                    </a>
                                </li>
                            </ul>
                            <div class="py-1">
                                <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto" x-data="{ exercises: {{ @json_encode($exercises) }}, selectAll: false }">
                    @include('admin.exercises.partials.table', ['exercises' => $exercises])
                </div>
            </div>
        </div>
    </section>

    @push('footer')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('filterData', () => ({
                showFilters: @json(!empty(request('status')) || !empty(request('category_id')) || !empty(request('establishment_id')) || !empty(request('created_from')) || !empty(request('created_to'))),
                filters: {
                    search: '{{ request('search', '') }}',
                    status: '{{ request('status', '') }}',
                    category_id: '{{ request('category_id', '') }}',
                    establishment_id: '{{ request('establishment_id', '') }}',
                    created_from: '{{ request('created_from', '') }}',
                    created_to: '{{ request('created_to', '') }}'
                },
                categories: @json($categories),
                establishments: @json($establishments),
                toggleFilters() {
                    this.showFilters = !this.showFilters;
                },
                hasActiveFilters() {
                    return this.filters.status || this.filters.category_id || this.filters.establishment_id || 
                           this.filters.created_from || this.filters.created_to || this.filters.search;
                },
                getFilterLabel(key, value) {
                    if (!value) return '';
                    
                    const labels = {
                        status: value === 'ativo' ? 'Status: Ativo' : 'Status: Inativo',
                        category_id: () => {
                            const cat = this.categories.find(c => c.id == value);
                            return cat ? 'Categoria: ' + cat.name : 'Categoria';
                        },
                        establishment_id: () => {
                            const estab = this.establishments.find(e => e.id == value);
                            return estab ? 'Estabelecimento: ' + estab.name : 'Estabelecimento';
                        },
                        created_from: 'De: ' + new Date(value).toLocaleDateString('pt-BR'),
                        created_to: 'Até: ' + new Date(value).toLocaleDateString('pt-BR')
                    };
                    
                    const label = labels[key];
                    return typeof label === 'function' ? label() : (label || key + ': ' + value);
                },
                removeFilter(key) {
                    this.filters[key] = '';
                    this.applyFilters();
                },
                clearFilters() {
                    // Redireciona para a rota sem parâmetros de query
                    window.location.href = '{{ route('admin.exercises.index') }}';
                },
                applyFilters() {
                    document.getElementById('filterForm').submit();
                }
            }))
        });
    </script>
    @endpush
</x-app-layout>

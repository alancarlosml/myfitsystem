@php
    $role = null;
    $user = null;
    if (Auth::guard('user')->check()) {
        $role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
        $user = Auth::user();
    } 
@endphp

<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 text-white">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Modalidades</h1>
                    <p class="mt-1 text-violet-100">Gerencie todas as modalidades do sistema</p>
                </div>
                @if($role && in_array($role->name, ['superuser', 'admin']))
                    <div class="flex items-center gap-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                            <p class="text-xs text-violet-200">Total de modalidades</p>
                            <p class="text-2xl font-bold text-white">{{ $modalities->count() }}</p>
                        </div>
                        <a href="{{ route('admin.modalities.create') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewbox="0 0 20 20">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Nova modalidade
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-success />

            @if($role && in_array($role->name, ['superuser']))
                <!-- Barra de Busca e Filtros -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                    <form method="GET" action="{{ route('admin.modalities.index') }}" id="filterForm" x-data="filterData" x-cloak>
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
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-500 focus:border-violet-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-violet-500 dark:focus:border-violet-500"
                                           placeholder="Buscar modalidades por nome ou descrição..."
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
                                        class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors">
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
                                <!-- Filtro por Status -->
                                <div>
                                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Status
                                    </label>
                                    <select name="status" id="status" x-model="filters.status"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-500 focus:border-violet-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-violet-500 dark:focus:border-violet-500">
                                        <option value="">Todos</option>
                                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                </div>

                                <!-- Filtro por Data de Cadastro (De) -->
                                <div>
                                    <label for="created_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Cadastrado a partir de
                                    </label>
                                    <input type="date" name="created_from" id="created_from" x-model="filters.created_from"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-500 focus:border-violet-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-violet-500 dark:focus:border-violet-500"
                                           value="{{ request('created_from') }}">
                                </div>

                                <!-- Filtro por Data de Cadastro (Até) -->
                                <div>
                                    <label for="created_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Cadastrado até
                                    </label>
                                    <input type="date" name="created_to" id="created_to" x-model="filters.created_to"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-500 focus:border-violet-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-violet-500 dark:focus:border-violet-500"
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900 dark:text-violet-200">
                                            <span x-text="getFilterLabel(key, value)"></span>
                                            <button type="button" @click="removeFilter(key)" 
                                                    class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-violet-200 dark:hover:bg-violet-700 transition-colors">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                </template>

                                <template x-if="filters.search">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                        Busca: "<span x-text="filters.search"></span>"
                                        <button type="button" @click="filters.search = ''; applyFilters()" 
                                                class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-purple-200 dark:hover:bg-purple-700 transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <button type="button" @click="clearFilters()"
                                        class="ml-auto text-xs text-violet-600 hover:text-violet-800 dark:text-violet-400 dark:hover:text-violet-300 font-medium">
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
                    <div class="overflow-x-auto" x-data="{ modalities: {{ @json_encode($modalities) }}, selectAll: false }">
                        @include('admin.modalities.partials.table', ['modalities' => $modalities])
                    </div>
                </div>
            @elseif($role && in_array($role->name, ['admin']))
                <!-- Seleção de Modalidades para Admin -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Selecionar Modalidades</h2>
                    <form method="POST" action="{{ route('admin.modalities.attach') }}">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 mb-6">
                            @foreach($modalities_admin as $modality)
                                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700 transition-colors">
                                    <input id="bordered-checkbox-{{ $modality->id }}" type="checkbox" value="{{ $modality->id }}"
                                           @if($establishment->modalities->contains($modality->id)) checked @endif
                                           name="modalities[]"
                                           class="w-4 h-4 text-violet-600 bg-gray-100 border-gray-300 rounded focus:ring-violet-500 dark:focus:ring-violet-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="bordered-checkbox-{{ $modality->id }}" class="w-full py-2 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">
                                        {{ $modality->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Salvar modalidades
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </section>

    @if($role && in_array($role->name, ['superuser']))
    @push('footer')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('filterData', () => ({
                showFilters: @json(!empty(request('status')) || !empty(request('created_from')) || !empty(request('created_to'))),
                filters: {
                    search: '{{ request('search', '') }}',
                    status: '{{ request('status', '') }}',
                    created_from: '{{ request('created_from', '') }}',
                    created_to: '{{ request('created_to', '') }}'
                },
                toggleFilters() {
                    this.showFilters = !this.showFilters;
                },
                hasActiveFilters() {
                    return this.filters.status || this.filters.created_from || this.filters.created_to || this.filters.search;
                },
                getFilterLabel(key, value) {
                    if (!value) return '';
                    
                    const labels = {
                        status: value === 'ativo' ? 'Status: Ativo' : 'Status: Inativo',
                        created_from: 'De: ' + new Date(value).toLocaleDateString('pt-BR'),
                        created_to: 'Até: ' + new Date(value).toLocaleDateString('pt-BR')
                    };
                    
                    return labels[key] || key + ': ' + value;
                },
                removeFilter(key) {
                    this.filters[key] = '';
                    this.applyFilters();
                },
                clearFilters() {
                    // Redireciona para a rota sem parâmetros de query
                    window.location.href = '{{ route('admin.modalities.index') }}';
                },
                applyFilters() {
                    document.getElementById('filterForm').submit();
                }
            }))
        });
    </script>
    @endpush
    @endif
</x-app-layout>

<x-app-layout>
    <!-- Header Moderno do Dashboard SaaS -->
    <div class="bg-gradient-to-r from-slate-800 via-gray-800 to-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Painel Executivo</h1>
                    <p class="mt-1 text-gray-300">Visão global do negócio</p>
                    <p class="mt-2 text-sm text-gray-400">{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-xs text-gray-300">Estabelecimentos Ativos</p>
                        <p class="text-2xl font-bold text-white">{{ $establishments_active ?? 0 }}</p>
                    </div>
                    <a href="{{ route('admin.establishments.index') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Gerenciar Estabelecimentos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-success />

            <!-- Métricas Principais de Negócio SaaS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- MRR -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">MRR</p>
                            <p class="text-3xl font-bold mt-1">R$ {{ number_format($mrr ?? 0, 2, ',', '.') }}</p>
                            <p class="text-xs text-blue-200 mt-1">Receita Recorrente Mensal</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- ARR -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">ARR</p>
                            <p class="text-3xl font-bold mt-1">R$ {{ number_format($arr ?? 0, 2, ',', '.') }}</p>
                            <p class="text-xs text-green-200 mt-1">Receita Recorrente Anual</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Contratos Expirando -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Expirando (30 dias)</p>
                            <p class="text-3xl font-bold mt-1">{{ $contracts_expiring_count ?? 0 }}</p>
                            <p class="text-xs text-orange-200 mt-1">R$ {{ number_format($contracts_expiring_value ?? 0, 2, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Churn Rate -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Taxa de Churn</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format(abs($churn_rate ?? 0), 1) }}%</p>
                            <p class="text-xs text-red-200 mt-1">Perda de clientes</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas Críticos -->
            @if(($contracts_expired_count ?? 0) > 0 || ($contracts_expiring_count ?? 0) > 0 || ($contracts_overdue ?? 0) > 0)
                <div class="mb-8 space-y-4">
                    @if(($contracts_expired_count ?? 0) > 0)
                        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">
                                        {{ $contracts_expired_count }} contrato(s) expirado(s) precisam de renovação
                                    </h3>
                                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                                        Valor total: R$ {{ number_format($contracts_expired_value ?? 0, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(($contracts_expiring_count ?? 0) > 0)
                        <div class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-orange-800 dark:text-orange-200">
                                        {{ $contracts_expiring_count }} contrato(s) expirando nos próximos 30 dias
                                    </h3>
                                    <p class="text-sm text-orange-700 dark:text-orange-300 mt-1">
                                        Valor total: R$ {{ number_format($contracts_expiring_value ?? 0, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(($contracts_overdue ?? 0) > 0)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">
                                        {{ $contracts_overdue }} contrato(s) com pagamento vencido
                                    </h3>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                        Valor total: R$ {{ number_format($vencidos ?? 0, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center p-4 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800 hover:shadow-md transition-all group">
                        <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Categorias</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Gerenciar categorias do sistema</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.modalities.index') }}" 
                       class="flex items-center p-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-lg border border-violet-200 dark:border-violet-800 hover:shadow-md transition-all group">
                        <div class="w-12 h-12 bg-violet-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Modalidades</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Gerenciar modalidades do sistema</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Gráfico de Receita e Métricas Secundárias -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Gráfico de Receita -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Evolução da Receita</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Últimos 6 Meses</span>
                        </div>
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Métricas Secundárias -->
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Receita do Mês</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($total_mes ?? 0, 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Receita do Ano</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($total_ano ?? 0, 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">A Receber</h3>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">R$ {{ number_format($a_receber_total ?? 0, 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Ticket Médio</h3>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">R$ {{ number_format($avg_revenue_per_establishment ?? 0, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contratos Expirando e Estabelecimentos com Problemas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Contratos Expirando -->
                @if(isset($contracts_expiring_soon) && $contracts_expiring_soon->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Contratos Expirando (30 dias)</h3>
                            <span class="px-3 py-1 text-xs font-semibold bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 rounded-full">
                                {{ $contracts_expiring_count }}
                            </span>
                        </div>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($contracts_expiring_soon->take(10) as $contract)
                                <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $contract->establishment->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Expira em {{ \Carbon\Carbon::parse($contract->end_date)->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            R$ {{ number_format($contract->amount, 2, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($contracts_expiring_soon->count() > 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.establishments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    Ver todos os {{ $contracts_expiring_soon->count() }} contratos
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Estabelecimentos com Problemas de Pagamento -->
                @if(isset($establishments_with_issues) && $establishments_with_issues->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Estabelecimentos com problemas no pagamento</h3>
                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                {{ $establishments_with_issues->count() }}
                            </span>
                        </div>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($establishments_with_issues->take(10) as $establishment)
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $establishment->name }}
                                        </p>
                                        <div class="flex gap-2 mt-1">
                                            @foreach($establishment->contracts->take(2) as $contract)
                                                <span class="px-2 py-1 text-xs font-medium rounded
                                                    {{ $contract->status === 'vencido' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                    {{ ucfirst($contract->status) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.establishments.view', $establishment->id) }}" 
                                       class="ml-4 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Top Estabelecimentos e Estatísticas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Estabelecimentos por Receita -->
                @if(isset($top_establishments) && $top_establishments->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Top Estabelecimentos por Receita</h3>
                        <div class="space-y-3">
                            @foreach($top_establishments as $index => $establishment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $establishment->name }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">R$ {{ number_format($establishment->total_revenue ?? 0, 2, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Estatísticas Gerais -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Estatísticas do Negócio</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total de Estabelecimentos:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $total_establishments ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Estabelecimentos Ativos:</span>
                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $establishments_active ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Estabelecimentos Inativos:</span>
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">{{ $establishments_inactive ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Novos este mês:</span>
                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $new_establishments_month ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Contratos Pendentes:</span>
                            <span class="text-sm font-semibold text-orange-600 dark:text-orange-400">{{ $contracts_pending_payment ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Crescimento Mensal:</span>
                            <span class="text-sm font-semibold {{ isset($revenue_growth) && $revenue_growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ isset($revenue_growth) ? number_format($revenue_growth, 1) : '0' }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>

@push('footer')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const chart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($monthLabels ?? []),
                datasets: [{
                    label: 'Receita (R$)',
                    data: @json($monthlyRevenues ?? []),
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush


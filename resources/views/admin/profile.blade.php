<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-slate-600 via-gray-600 to-zinc-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">👤 Meu Perfil Administrativo</h1>
                    <p class="mt-1 text-slate-200">@if($role && in_array($role->name, ['superuser'])) Superusuário @else Administrador @endif da plataforma</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-sm font-medium text-white">{{ $basicInfo['lastAccess'] }}</p>
                        <p class="text-xs text-slate-200">Último acesso</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-3xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Seções do Perfil Administrativo -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Seção Principal (2/3) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Informações Pessoais -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-600 to-gray-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informações Pessoais
                            </h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome Completo</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $basicInfo['fullName'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $basicInfo['email'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $basicInfo['phone'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Membro desde</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $basicInfo['joined'] }}</dd>
                                </div>
                            </dl>

                            <div class="mt-6">
                                <button class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    ✏️ Editar Informações
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Estabelecimento (se houver) -->
                    @if($establishmentInfo)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-green-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Estabelecimento Administrado
                            </h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome do Estabelecimento</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $establishmentInfo['name'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $establishmentInfo['address'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $establishmentInfo['phone'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $establishmentInfo['email'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Alunos</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $establishmentInfo['studentsCount'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contratos Ativos</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $establishmentInfo['activeContracts'] }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Painel Lateral -->
                <div class="space-y-6">

                    <!-- Estatísticas Administrativas -->
                    @if($managementStats)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Estatísticas Gerenciais
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Estabelecimentos</span>
                                <div class="bg-indigo-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $managementStats['totalEstablishments'] }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Ativos</span>
                                <div class="bg-green-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $managementStats['activeEstablishments'] }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Total de Alunos</span>
                                <div class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $managementStats['totalStudents'] }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Receita Total</span>
                                <div class="bg-emerald-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    R$ {{ number_format($managementStats['totalRevenue'] ?? 0, 2, ',', '.') }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Receita Mensal</span>
                                <div class="bg-purple-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    R$ {{ number_format($managementStats['monthlyRevenue'] ?? 0, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tarefas Pendentes -->
                    @if($pendingTasks)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Requer Atenção
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Contratos Expirando</span>
                                <div class="bg-red-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $pendingTasks['expiringContracts'] ?? 0 }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Novos Alunos (Mês)</span>
                                <div class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $pendingTasks['newStudentsThisMonth'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Configurações -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Configurações da Conta
                        </h3>

                        <div class="space-y-3">
                            <button class="w-full bg-slate-600 hover:bg-slate-700 text-white py-3 px-4 rounded-lg transition-colors duration-200 font-medium text-left">
                                🔐 Alterar Senha
                            </button>
                            <button class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg transition-colors duration-200 font-medium text-left">
                                📧 Preferências de Email
                            </button>
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-colors duration-200 font-medium text-left">
                                🔔 Configurações de Notificação
                            </button>
                            <button class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 px-4 rounded-lg transition-colors duration-200 font-medium text-left">
                                🌙 Tema da Interface
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>

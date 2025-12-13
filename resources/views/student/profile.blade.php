@php
    // Usar layout mobile apenas se for dispositivo móvel
    $layout = (!empty($isMobile) && $isMobile === true) ? 'layouts.student-mobile' : 'layouts.app';
@endphp
@extends($layout)

@section('content')
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">👤 Meu Perfil</h1>
                    <p class="mt-1 text-blue-100">Gerencie suas informações pessoais e veja suas estatísticas</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-sm font-medium text-white">Membro desde {{ \Carbon\Carbon::parse($student->created_at)->locale('pt_BR')->isoFormat('DD/MM/YYYY') }}</p>
                        <p class="text-xs text-blue-100">Último acesso: hoje</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-3xl font-bold">{{ substr($student->name, 0, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="profileData()" class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Seções do Perfil -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Seção Principal (2/3) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Informações Pessoais -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4">
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
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $student->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $student->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $student->phone ?? 'Não informado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Nascimento</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">
                                        @if($student->birth_date)
                                            {{ \Carbon\Carbon::parse($student->birth_date)->locale('pt_BR')->isoFormat('DD/MM/YYYY') }} ({{ \Carbon\Carbon::parse($student->birth_date)->age }} anos)
                                        @else
                                            Não informado
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Altura</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $student->height ? number_format($student->height, 2) . 'm' : 'Não informado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Peso</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $student->weight ? number_format($student->weight, 1) . 'kg' : 'Não informado' }}</dd>
                                </div>
                            </dl>

                            <div class="mt-6">
                                <button @click="showEditModal = true"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    ✏️ Editar Informações
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-green-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Endereço
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($student->address)
                                <address class="not-italic text-gray-900 dark:text-white leading-relaxed">
                                    {{ $student->address }}<br>
                                    @if($student->city && $student->state)
                                        {{ $student->city }}, {{ $student->state }}
                                    @endif
                                    @if($student->zip_code)
                                        • CEP: {{ $student->zip_code }}
                                    @endif
                                </address>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">Nenhum endereço cadastrado</p>
                            @endif

                            <div class="mt-6">
                                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    📍 Editar Endereço
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Próximas Aulas -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Próximas Aulas ({{ $upcomingClasses->count() }})
                            </h2>
                        </div>
                        <div class="p-6">
                            @forelse($upcomingClasses as $booking)
                                <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg mb-3">
                                    <div class="flex-shrink-0 w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-lg">{{ \Carbon\Carbon::parse($booking->class_date)->format('d') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $booking->classSchedule->modality->name ?? 'Aula' }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($booking->class_date)->locale('pt_BR')->isoFormat('dddd, DD/MM/YYYY [às] HH:mm') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Sala: {{ $booking->classSchedule->class_room ?? 'Principal' }}</p>
                                    </div>
                                    <div class="text-green-500">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 mb-2">Nenhuma aula próxima</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Suas próximas aulas aparecerão aqui</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Painel Lateral -->
                <div class="space-y-6">

                    <!-- Estatísticas Rápidas -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Meus Números
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Total de Aulas</span>
                                <div class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $stats['totalClasses'] ?? 0 }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Este Mês</span>
                                <div class="bg-green-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $stats['monthlyClasses'] ?? 0 }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Esta Semana</span>
                                <div class="bg-purple-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $stats['weeklyClasses'] ?? 0 }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Sequência</span>
                                <div class="bg-orange-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    {{ $stats['streakDays'] ?? 0 }} dias
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Conquistas</span>
                                <div class="bg-yellow-500 text-white rounded-full px-3 py-1 text-sm font-medium">
                                    🏆 {{ $stats['achievements'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Plano Atual -->
                    @if($currentContract)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Plano Atual
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Plano</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($currentContract->service_name) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Valor</span>
                                <span class="font-semibold text-gray-900 dark:text-white">R$ {{ number_format($currentContract->amount, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Validade</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($currentContract->end_date)->locale('pt_BR')->isoFormat('DD/MM/YYYY') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $currentContract->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pagamentos Pendentes -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Próximos Pagamentos
                        </h3>

                        @forelse($upcomingPayments as $payment)
                            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-red-800 dark:text-red-200">{{ $payment['type'] }}</span>
                                    <span class="text-sm font-medium text-red-600 dark:text-red-400">{{ $payment['formatted_date'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-red-600 dark:text-red-400">{{ $payment['status'] }}</span>
                                    <span class="font-bold text-red-800 dark:text-red-200">{{ $payment['amount'] }}</span>
                                </div>
                                <button class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                                    💳 Pagar Agora
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum pagamento pendente</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Avaliações Físicas Recentes -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Últimas Avaliações
                        </h3>

                        @forelse($recentAssessments as $assessment)
                            <div class="bg-cyan-50 dark:bg-cyan-900/20 p-4 rounded-lg mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-cyan-800 dark:text-cyan-200">{{ \Carbon\Carbon::parse($assessment->assessment_date)->locale('pt_BR')->isoFormat('DD/MM/YYYY') }}</span>
                                    <span class="text-sm text-cyan-600 dark:text-cyan-400">{{ $assessment->weight ?? 'N/A' }}kg</span>
                                </div>
                                <p class="text-xs text-cyan-600 dark:text-cyan-400">
                                    IMC: {{ $assessment->bmi ?? 'N/A' }} • Músculos: {{ $assessment->muscle_mass ?? 'N/A' }}
                                </p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-3">Nenhuma avaliação física recente</p>
                                <a href="{{ route('student.physical_assessments.index') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors inline-block">
                                    📋 Agendar Avaliação
                                </a>
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Modal de Edição de Perfil -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="transform scale-95"
             x-transition:enter-end="transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="transform scale-100"
             x-transition:leave-end="transform scale-95">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-white">✏️ Editar Informações Pessoais</h3>
                    <button @click="showEditModal = false"
                            class="text-white/80 hover:text-white text-2xl font-medium">
                        &times;
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form @submit.prevent="updateProfile()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome Completo *
                            </label>
                            <input type="text"
                                   x-model="editForm.name"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email *
                            </label>
                            <input type="email"
                                   x-model="editForm.email"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                   required>
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Telefone
                            </label>
                            <input type="tel"
                                   x-model="editForm.phone"
                                   placeholder="(11) 99999-9999"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Data de Nascimento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data de Nascimento
                            </label>
                            <input type="date"
                                   x-model="editForm.birth_date"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Altura -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Altura (em metros)
                            </label>
                            <input type="number"
                                   step="0.01"
                                   min="0.5"
                                   max="3.0"
                                   x-model="editForm.height"
                                   placeholder="1.85"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Peso -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Peso (em kg)
                            </label>
                            <input type="number"
                                   step="0.1"
                                   min="10"
                                   max="300"
                                   x-model="editForm.weight"
                                   placeholder="75.5"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Endereço Completo -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Endereço Completo
                            </label>
                            <textarea x-model="editForm.address"
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-vertical"
                                      placeholder="Rua, Bairro, Número"></textarea>
                        </div>

                        <!-- Grid para Cidade, Estado, CEP -->
                        <div class="grid grid-cols-3 gap-4 md:col-span-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Cidade
                                </label>
                                <input type="text"
                                       x-model="editForm.city"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Estado
                                </label>
                                <input type="text"
                                       x-model="editForm.state"
                                       maxlength="2"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       placeholder="SP">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    CEP
                                </label>
                                <input type="text"
                                       x-model="editForm.zip_code"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       placeholder="01234-567">
                            </div>
                        </div>
                    </div>

                    <!-- Mensagens de Status -->
                    <div x-show="updateMessage"
                         :class="updateSuccess ? 'bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200' : 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200'"
                         class="mt-6 p-4 rounded-lg">
                        <p x-text="updateMessage"></p>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex justify-end space-x-3 mt-8">
                        <button @click="showEditModal = false"
                                type="button"
                                class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                                :disabled="isUpdating"
                                :class="isUpdating ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                                class="px-6 py-2 text-white rounded-lg transition-colors flex items-center">
                            <span x-show="!isUpdating">💾 Salvar Alterações</span>
                            <span x-show="isUpdating" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Salvando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileData', () => ({
                showEditModal: false,
                isUpdating: false,
                updateMessage: '',
                updateSuccess: false,

                editForm: {
                    name: '{{ $student->name }}',
                    email: '{{ $student->email }}',
                    phone: '{{ $student->phone ?? "" }}',
                    birth_date: '{{ $student->birth_date }}',
                    height: '{{ $student->height }}',
                    weight: '{{ $student->weight }}',
                    address: '{{ $student->address ?? "" }}',
                    city: '{{ $student->city ?? "" }}',
                    state: '{{ $student->state ?? "" }}',
                    zip_code: '{{ $student->zip_code ?? "" }}',
                },

                async updateProfile() {
                    this.isUpdating = true;
                    this.updateMessage = '';

                    try {
                        const response = await fetch('/app/perfil/atualizar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.editForm)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.updateSuccess = true;
                            this.updateMessage = 'Perfil atualizado com sucesso!';
                            // Atualizar dados na página
                            location.reload();
                        } else {
                            this.updateSuccess = false;
                            this.updateMessage = result.message || 'Erro ao atualizar perfil';
                        }
                    } catch (error) {
                        console.error('Erro ao atualizar perfil:', error);
                        this.updateSuccess = false;
                        this.updateMessage = 'Erro inesperado. Tente novamente.';
                    }

                    this.isUpdating = false;

                    // Esconder mensagem após alguns segundos
                    setTimeout(() => {
                        this.updateMessage = '';
                    }, 5000);
                }
            }))
        });
    </script>
@endsection

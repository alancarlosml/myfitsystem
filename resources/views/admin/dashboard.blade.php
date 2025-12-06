@php
    $role = null;
    $isSuperuser = false;
    
    if (Auth::guard('user')->check()) {
        $user = Auth::user();
        
        // Verificar se é superuser primeiro (superuser pode não ter establishment_id)
        $superuserRole = \App\Models\Role::where('name', 'superuser')->first();
        if ($superuserRole) {
            $isSuperuser = \DB::table('role_user')
                ->where('user_id', $user->id)
                ->where('role_id', $superuserRole->id)
                ->exists();
        }
        
        // Se for superuser, criar um role fake para facilitar as verificações
        if ($isSuperuser) {
            $role = (object)['name' => 'superuser'];
        } else {
            // Para usuários normais, pegar o role do establishment
            $role = $user->getRoleForEstablishment(Session::get('establishment_id'));
        }
    }
@endphp

<x-app-layout>
    <!-- Header Moderno do Dashboard -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        {{ $role && in_array($role->name, ['superuser']) ? 'Visão Empresarial' : ($current_establishment->name ?? '') }}
                    </h1>
                    <p class="mt-1 text-blue-100">{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                    @if($role && $role->name === 'superuser')
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <a href="{{ route('admin.establishments.index') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-900 bg-white/90 rounded-lg hover:bg-white transition-colors">
                                Ver estabelecimentos
                            </a>
                            <a href="{{ route('select.establishment') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
                                Selecionar estabelecimento
                            </a>
                        </div>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-xs text-blue-200">Bem-vindo</p>
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Ações Rápidas -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @if ($role && in_array($role->name, ['superuser']))
                        <a href="{{ route('admin.students.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 group">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Novo Aluno</span>
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 group">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Novo Colaborador</span>
                        </a>
                        <a href="{{ route('admin.establishments.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 group">
                            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Novo Estabelecimento</span>
                        </a>
                    @else
                        <a href="{{ route('admin.students.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 group">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Novo Aluno</span>
                        </a>
                        <a href="{{ route('admin.class_schedules.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 group">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Nova Aula</span>
                        </a>
                    @endif
                    <a href="{{ route('admin.exercises.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 group">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Novo Exercício</span>
                    </a>
                    <a href="{{ route('admin.workouts.index') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 group">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Treinos</span>
                    </a>
                </div>
            </div>

            <!-- Key Metrics Cards -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @if ($role && in_array($role->name, ['superuser']))
                    <x-card title="Estabelecimentos Ativos" color="blue" href="{{ route('admin.establishments.index') }}"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'>
                        {{$establishments_active ?? 0}}
                    </x-card>
                    <x-card title="Total de Alunos" color="green" href="{{ route('admin.students.index') }}"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                            :trend="isset($new_students_month) && $new_students_month > 0 ? ['direction' => 'up', 'value' => $new_students_month, 'label' => 'este mês'] : null">
                        {{$total_students ?? 0}}
                    </x-card>
                    <x-card title="Total em {{ date('M') }}" color="purple"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'>
                        R$ {{ number_format($total_mes ?? 0, 2, ',', '.') }}
                    </x-card>
                    <x-card title="Crescimento Mensal" color="{{ isset($revenue_growth) && $revenue_growth >= 0 ? 'green' : 'red' }}"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>'>
                        @if(isset($revenue_growth))
                            {{ number_format(abs($revenue_growth), 1) }}%
                        @else
                            0%
                        @endif
                    </x-card>
                @else
                    <x-card title="Alunos Ativos" color="blue" href="{{ route('admin.students.index') }}"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'>
                        {{$students_active ?? 0}}
                    </x-card>
                    <x-card title="Receita Mensal" color="green"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'>
                        R$ {{ number_format($total_mes ?? 0, 2, ',', '.') }}
                    </x-card>
                    <x-card title="Crescimento" color="{{ isset($revenue_growth) && $revenue_growth >= 0 ? 'green' : 'red' }}"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>'>
                        @if(isset($revenue_growth))
                            {{ number_format(abs($revenue_growth), 1) }}%
                        @else
                            0%
                        @endif
                    </x-card>
                    <x-card title="Aulas Hoje" color="orange" href="{{ route('admin.class_schedules.index') }}"
                            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'>
                        {{$classes_today ?? 0}}
                    </x-card>
                @endif
            </section>

            <!-- Ações Rápidas -->
            @if($isSuperuser || ($role && in_array($role->name, ['admin'])))
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
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $isSuperuser ? 'Gerenciar categorias do sistema' : 'Gerenciar suas categorias' }}</p>
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
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $isSuperuser ? 'Gerenciar modalidades do sistema' : 'Gerenciar suas modalidades' }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endif

            <!-- Detailed Metrics -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Evolução da Receita</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Últimos 6 Meses</span>
                        </div>
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-4">
                    @if ($role && in_array($role->name, ['superuser']))
                        <x-card title="Receita Anual Total" color="indigo">
                            R$ {{ number_format($total_ano ?? 0, 2, ',', '.') }}
                        </x-card>
                        <x-card title="Novos Alunos (Mês)" color="blue">
                            {{$new_students_month ?? 0}}
                        </x-card>
                        <x-card title="Reservas no Mês" color="green">
                            {{$total_class_bookings_month ?? 0}}
                        </x-card>
                        <x-card title="Pagamentos Pendentes" color="red">
                            {{ $pending_payments ?? 0 }}
                        </x-card>
                    @else
                        <x-card title="Receita Anual" color="indigo">
                            R$ {{ number_format($total_ano ?? 0, 2, ',', '.') }}
                        </x-card>
                        <x-card title="Alunos Cadastrados" color="blue">
                            {{$total_students ?? 0}}
                        </x-card>
                        <x-card title="Reservas no Mês" color="green">
                            {{$class_bookings_this_month ?? 0}}
                        </x-card>
                        <x-card title="Equipe" color="purple">
                            {{$total_staff ?? 0}} membros
                        </x-card>
                    @endif
                </div>
            </section>

            <!-- Detailed Insights -->
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if ($role && in_array($role->name, ['superuser']))
                    <!-- TOP PERFORMING ESTABLISHMENTS -->
                    @if(isset($top_establishments) && $top_establishments->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Top Estabelecimentos por Receita</h3>
                            <div class="space-y-3">
                                @foreach($top_establishments as $index => $establishment)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
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

                    <!-- POPULAR MODALITIES -->
                    @if(isset($popular_modalities) && $popular_modalities->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Modalidades Mais Procuradas</h3>
                            <div class="space-y-3">
                                @foreach($popular_modalities as $modality)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $modality->name }}</span>
                                        <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                            {{ $modality->class_schedules_count ?? 0 }} aulas
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <!-- POPULAR MODALITIES IN ESTABLISHMENT -->
                    @if(isset($popular_modalities) && $popular_modalities->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Modalidades Mais Procuradas</h3>
                            <div class="space-y-3">
                                @foreach($popular_modalities as $modality)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $modality->name }}</span>
                                        <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                            {{ $modality->schedule_count ?? 0 }} aulas
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- MONTHLY INSIGHTS -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Indicadores Mensais</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Novos alunos:</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $enrolled_students_this_month ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Reservas realizadas:</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $class_bookings_this_month ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pagamentos pendentes:</span>
                                <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ $pending_payments ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Taxa de ocupação:</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $classes_today ?? 0 }} aulas hoje</span>
                            </div>
                        </div>
                    </div>
                @endif
            </section>

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
                labels: @json($monthLabels),
                datasets: [{
                    label: 'Receitas',
                    data: @json($monthlyRevenues),
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

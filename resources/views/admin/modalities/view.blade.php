@php
    $role = null;
    $user = null;
    if (Auth::guard('user')->check()) {
        $role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
        $user = Auth::user();
    } 
@endphp

<x-app-layout>
    <x-header>
        <x-slot:title>Modalidade - Detalhes</x-slot:title>
    </x-header>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 mx-full">
            @if ($role && in_array($role->name, ['superuser']))
            <h2 class="text-4xl font-extrabold dark:text-white">
                <small class="font-semibold text-gray-500 dark:text-gray-400">{{$modality->establishment->name}}</small>
            </h2>
            @endif
            <div class="py-8 mx-auto w-full">
                <div class="mx-auto">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $modality->name }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                            <span class="text-gray-900 dark:text-white">
                                {{ $modality->description }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="active"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stataus</label>
                            <span class="text-gray-900 dark:text-white">
                                @if ($modality->active == 1)
                                    {{ 'Ativo' }}
                                @elseif ($modality->active == 0)
                                    {{ 'Inativo' }}
                                @endif
                        </div>
                    </div>
                </div>
            </div>  
            
            <div class="mt-4 flex justify-end">
                <a href="{{ route('admin.modalities.index') }}"
                   class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Voltar</a>
            </div>
        </div>
    </section>
</x-app-layout>

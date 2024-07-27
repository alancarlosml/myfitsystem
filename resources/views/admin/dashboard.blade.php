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
        <x-slot:title>Painel de controle</x-slot:title>
    </x-header>

    <section class="mt-8 flex flex-col md:flex-row gap-8">
        @if ($role && in_array($role->name, ['superuser']))
        <x-card>
            <x-slot:title>Estabelecimentos ativos</x-slot:title>
            59
        </x-card>
        <x-card>
            <x-slot:title>Estabelecimentos inativos</x-slot:title>
            12
        </x-card>
        @endif
        @if ($role && in_array($role->name, ['admin']))
        <x-card>
            <x-slot:title>Alunos ativos</x-slot:title>
            59
        </x-card>
        <x-card>
            <x-slot:title>Alunos inativos</x-slot:title>
            12
        </x-card>
        @endif
        @if ($role && in_array($role->name, ['superuser','admin']))
        <x-card>
            <x-slot:title>Total em Julho</x-slot:title>
            R$ 13.430,98
        </x-card>
        <x-card>
            <x-slot:title>Total em {{ date('Y') }}</x-slot:title>
            R$ 73.430,98
        </x-card>
        @endif
    </section>

    {{--<section class="mt-12">
        <x-table />
    </section>--}}

</x-app-layout>
@php
    $role = null;
    $user = null;
    if (Auth::guard('user')->check()) {
        $role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
        $user = Auth::user();
    } 
    $date = new DateTime();
    $formatter = new IntlDateFormatter(
    'pt_BR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'America/Sao_Paulo',          
        IntlDateFormatter::GREGORIAN
    );

    $monthName = $formatter->format($date);
    $monthName = explode(' ', $monthName)[3];
@endphp

<x-app-layout>
    <x-header>
        <x-slot:title>Painel de controle</x-slot:title>
    </x-header>

    <section class="mt-8 flex flex-col md:flex-row gap-8">
        @if ($role && in_array($role->name, ['superuser']))
        <x-card>
            <x-slot:title>Estabelecimentos ativos</x-slot:title>
            {{$establishments_active}}
        </x-card>
        <x-card>
            <x-slot:title>Estabelecimentos inativos</x-slot:title>
            {{$establishments_inactive}}
        </x-card>
        @endif
        @if ($role && in_array($role->name, ['admin']))
        <x-card>
            <x-slot:title>Alunos ativos</x-slot:title>
            {{$students_active}}
        </x-card>
        <x-card>
            <x-slot:title>Alunos inativos</x-slot:title>
            {{$students_inactive}}
        </x-card>
        @endif
        @if ($role && in_array($role->name, ['superuser','admin']))
        <x-card>
            <x-slot:title>Total em {{ucfirst($monthName)}}</x-slot:title>
            R$ {{$total_mes}}
        </x-card>
        <x-card>
            <x-slot:title>Total em {{ date('Y') }}</x-slot:title>
            R$ {{$total_ano}}
        </x-card>
        @endif
    </section>

    {{--<section class="mt-12">
        <x-table />
    </section>--}}

</x-app-layout>
<x-app-layout>
    <x-header>
        <x-slot:title>Painel de controle</x-slot:title>
    </x-header>

    <section class="mt-8 flex flex-col md:flex-row gap-8">
        <x-card>
            <x-slot:title>Aulas em Junho</x-slot:title>
            13
        </x-card>
        <x-card>
            <x-slot:title>Aulas em {{ date('Y') }}</x-slot:title>
            45
        </x-card>
    </section>

    <section class="mt-12">
        <x-table />
    </section>

</x-app-layout>
<x-app-layout>
    <x-header>
        <x-slot:title>Painel de controle</x-slot:title>
    </x-header>

    {{-- {{dd(Auth::guard('user')->user()->id)}} --}}

    <section class="mt-8 flex flex-col md:flex-row gap-8">
        <x-card>
            <x-slot:title>Estabelecimentos ativos</x-slot:title>
            59
        </x-card>
        <x-card>
            <x-slot:title>Estabelecimentos inativos</x-slot:title>
            12
        </x-card>
        <x-card>
            <x-slot:title>Total em Junho</x-slot:title>
            R$ 13.430,98
        </x-card>
        <x-card>
            <x-slot:title>Total em {{ date('Y') }}</x-slot:title>
            R$ 73.430,98
        </x-card>
    </section>

    <section class="mt-12">
        <x-table />
    </section>

</x-app-layout>
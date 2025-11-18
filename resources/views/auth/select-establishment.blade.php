<x-guest-layout>
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
                <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Selecione um Estabelecimento</h2>
                <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">Escolha um estabelecimento para continuar.</p>
            </div> 
            <div class="grid gap-8 lg:grid-cols-2">
                @forelse($establishments as $establishment)
                <article class="p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $establishment->name }}
                    </h2>
                    <p class="mb-5 font-light text-gray-500 dark:text-gray-400">
                        {{ ucfirst($establishment->role_name) }}
                    </p>
                    @if(isset($establishment->contract_active) && !$establishment->contract_active)
                        <p class="mb-4 text-sm font-semibold text-red-600 dark:text-red-400">Contrato do sistema expirado</p>
                    @endif
                    <div class="flex justify-between items-center">
                        <form action="{{ route('store.establishment') }}" method="POST">
                            @csrf
                            <input type="hidden" name="establishment_id" value="{{ $establishment->id }}">
                            <button type="submit" class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline">
                                Selecionar
                                <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </article> 
                @empty
                <div class="lg:col-span-2">
                    <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200">
                        <h3 class="text-lg font-semibold mb-2">Nenhum estabelecimento disponível</h3>
                        <p class="text-sm">Não encontramos estabelecimentos com contrato ativo para o seu usuário. Entre em contato com a administração para regularizar o acesso.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</x-guest-layout>

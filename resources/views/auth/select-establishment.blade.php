<x-guest-layout>
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
                <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Selecione um Estabelecimento</h2>
                <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">Escolha um estabelecimento para continuar.</p>
            </div> 
            <div class="grid gap-8 lg:grid-cols-2">
                @foreach($establishments as $establishment)
                <article class="p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                    {{-- <div class="flex justify-between items-center mb-5 text-gray-500">
                        <span class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                            @foreach($establishment->roles as $role)
                                <p class="mb-5 font-light text-gray-500 dark:text-gray-400">Papel: {{ $role->pivot->role_id }}</p>
                            @endforeach
                        </span>
                    </div> --}}
                    <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $establishment->name }}</h2>
                    <p class="mb-5 font-light text-gray-500 dark:text-gray-400">
                        @if ($establishment->type == 'academia')
                            {{ 'Academia' }}
                        @elseif ($establishment->type == 'crossfit')
                            {{ 'Crossfit' }}
                        @elseif ($establishment->type == 'personal_trainer')
                            {{ 'Personal trainer' }}
                        @else
                            {{'Estabelecimento não identificado'}}
                        @endif
                    </p>
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
                @endforeach
            </div>  
        </div>
    </section>
</x-guest-layout>

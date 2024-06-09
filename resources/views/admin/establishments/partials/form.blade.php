@csrf
<div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
    <div class="sm:col-span-2">
        <label for="type"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <select id="type" name="type"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option selected="">Selecione</option>
            @foreach ($types as $type)
                <option value="{{ $type }}"
                        {{ old('type', $establishment->type ?? '') == $type ? 'selected' : '' }}>
                    @if ($type == 'crossfit')
                        {{ 'Crossfit' }}
                    @elseif ($type == 'academia')
                        {{ 'Academia' }}
                    @elseif ($type == 'personal_trainer')
                        {{ 'Personal trainer' }}
                    @endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="name"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
        <input type="text" name="name" id="name"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $establishment->name ?? old('name') }}"
               placeholder="Nome do estabelecimento">
    </div>
    <div class="sm:col-span-2">
        <label for="owner"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proprietário</label>
        <input type="text" name="owner" id="owner"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $establishment->owner ?? old('owner') }}"
               placeholder="Proprietário do estabelecimento">
    </div>
    <div class="w-full">
        <label for="cnpj"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CNPJ</label>
        <input type="text" name="cnpj" id="cnpj"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $establishment->cnpj ?? old('cnpj') }}"
               placeholder="CNPJ">
    </div>
    <div class="w-full">
        <label for="phone"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone</label>
        <input type="text" name="phone" id="phone"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $establishment->phone ?? old('phone') }}"
               placeholder="Telefone">
    </div>
    <div>
        <label for="social_network"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rede social</label>
        <input type="text" name="social_network" id="social_network"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="@redesocial"
               value="{{ $establishment->social_network ?? old('social_network') }}">
    </div>
    <div>
        <label for="website"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Site</label>
        <input type="text" name="website" id="website"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="www.site.com.br"
               value="{{ $establishment->website ?? old('website') }}">
    </div>
    <div class="sm:col-span-2">
        <label for="address"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Endereço</label>
        <textarea id="address" rows="8"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                  placeholder="Endereço" name="address">{{ $establishment->address ?? old('address') }}</textarea>
    </div>
    <div class="sm:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" value="1" class="sr-only peer" name="active"
                   {{ old('active', $establishment->active ?? false) ? 'checked' : '' }}>
            <div
                 class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
        </label>
    </div>
</div>
<div class="mt-4 flex justify-end">
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
        {{ isset($establishment) ? 'Atualizar Estabelecimento' : 'Criar Estabelecimento' }}
    </button>
    <a href="{{ route('admin.establishments.index') }}"
       class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</a>
</div>

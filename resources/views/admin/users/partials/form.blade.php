@csrf
<div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

    <div class="sm:col-span-2">
        <label for="name"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
        <input type="text" name="name" id="name"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $user->name ?? old('name') }}"
               placeholder="Nome">
    </div>
    <div class="sm:col-span-2">
        <label for="cpf"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CPF</label>
        <input type="text" name="cpf" id="cpf"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $user->cpf ?? old('cpf') }}"
               placeholder="CPF">
    </div>
    <div class="w-full">
        <label for="email"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
        <input type="text" name="email" id="email"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $user->email ?? old('email') }}"
               placeholder="Email">
    </div>
    <div>
        <label for="phone"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone</label>
        <input type="text" name="phone" id="phone"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="(00) 0000-0000"
               value="{{ $user->phone ?? old('phone') }}">
    </div>
    @if (!isset($user))
        <div>
            <label for="establishment"
                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estabelecimento</label>
            <select id="establishment" name="establishment"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                <option selected="">Selecione</option>
                @foreach ($establishments as $establishment)
                    <option value="{{ $establishment->id }}">
                        {{ ucfirst($establishment->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Papel</label>
            <select id="role" name="role"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                <option selected="">Selecione</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">
                        {{ ucfirst($role->role) }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="w-full">
        <label for="password"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha</label>
        <input type="password" name="password" id="password"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="Senha">
    </div>
    <div>
        <label for="password_confirmation"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar senha</label>
        <input type="password" name="password_confirmation" id="password_confirmation"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="Confirmar senha">
    </div>
    <div class="sm:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" value="1" class="sr-only peer" name="active"
                   {{ old('active', $user->active ?? false) ? 'checked' : '' }}>
            <div
                 class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
        </label>
    </div>
</div>
<div class="mt-4 flex justify-end">
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
        {{ isset($user) ? 'Atualizar Usuário' : 'Criar Usuário' }}
    </button>
    <a href="{{ route('admin.users.index') }}"
       class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</a>
</div>

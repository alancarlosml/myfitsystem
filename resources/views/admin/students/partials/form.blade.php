@csrf
<div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
    <div class="sm:col-span-2">
        <div class="flex justify-left px-4 pt-4">
            <button id="dropdownButton" data-dropdown-toggle="dropdown"
                    class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5"
                    type="button">
                <span class="sr-only">Open dropdown</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                     viewBox="0 0 16 3">
                    <path
                          d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="dropdown"
                 class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2" aria-labelledby="dropdownButton">
                    <li>
                        <a href="#"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Editar
                            foto</a>
                    </li>
                    <li>
                        <a href="#"
                           class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Deletar
                            foto</a>
                    </li>
                </ul>
            </div>
        </div>
        <img class="rounded-full w-72 h-72" src="{{ $student->profile_picture }}" alt="image description">
    </div>

    <div class="sm:col-span-2">
        <label for="name"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
        <input type="text" name="name" id="name"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $student->name ?? old('name') }}"
               placeholder="Nome">
    </div>
    <div class="sm:col-span-2">
        <label for="cpf"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CPF</label>
        <input type="text" name="cpf" id="cpf"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $student->cpf ?? old('cpf') }}"
               placeholder="CPF">
    </div>
    <div class="w-full">
        <label for="email"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
        <input type="text" name="email" id="email"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $student->email ?? old('email') }}"
               placeholder="Email">
    </div>
    <div class="w-full">
        <label for="birthdate"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data de nascimento</label>
        <input type="text" name="birthdate" id="birthdate"
               class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               value="{{ $student->birthdate ?? old('birthdate') }}"
               placeholder="Data de nascimento">
    </div>
    <div>
        <label for="phone"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone</label>
        <input type="text" name="phone" id="phone"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="(00) 0000-0000"
               value="{{ $student->phone ?? old('phone') }}">
    </div>
    <div>
        <label for="gender"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sexo</label>
        <select id="gender" name="gender"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option selected="">Selecione</option>
            @foreach ($genders as $gender)
                <option value="{{ $gender }}"
                        {{ old('gender', $student->gender ?? '') == $gender ? 'selected' : '' }}>
                    @if ($gender == 'masculino')
                        {{ 'Masculino' }}
                    @elseif ($gender == 'feminino')
                        {{ 'Feminino' }}
                    @elseif ($gender == 'outro')
                        {{ 'Outro' }}
                    @endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="address"
               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Endereço</label>
        <textarea id="address" rows="8"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                  placeholder="Endereço" name="address">{{ $student->address ?? old('address') }}</textarea>
    </div>
    <div class="sm:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" value="1" class="sr-only peer" name="active"
                   {{ old('active', $student->active ?? false) ? 'checked' : '' }}>
            <div
                 class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
        </label>
    </div>
</div>
<div class="mt-4 flex justify-end">
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
        {{ isset($student) ? 'Atualizar Aluno' : 'Criar Aluno' }}
    </button>
    <a href="{{ route('admin.students.index') }}"
       class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</a>
</div>

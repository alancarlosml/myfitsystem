@php
    $role = null;
    $user = null;
    if (Auth::guard('user')->check()) {
        $role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
        $user = Auth::user();
    } 
@endphp

@csrf
<div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
    @if ($role && in_array($role->name, ['superuser']))
        @if (isset($exercise))
            <h2 class="text-4xl font-extrabold dark:text-white">
                <small class="font-semibold text-gray-500 dark:text-gray-400">{{$exercise->establishment->name}}</small>
                <input type="hidden" name="establishment_id" value="{{ $exercise->establishment->id }}">
            </h2>
            {{-- <h2 class="text-4xl font-extrabold dark:text-white">
                <small class="font-semibold text-gray-500 dark:text-gray-400">{{$exercise->category->name}}</small>
                <input type="hidden" name="category_id" value="{{ $exercise->category->id }}">
            </h2> --}}
        @else        
        <div class="w-full">
            <label for="establishment_id"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Establecimento</label>
            <select id="establishment_id" name="establishment_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                <option selected="">Selecione</option>
                @foreach ($establishments as $establishment)
                    <option value="{{ $establishment->id }}" {{ old('establishment_id') == $establishment->id ? 'selected' : '' }}>
                        {{ $establishment->name }}
                    </option>
                @endforeach
            </select>
        </div> 
        @endif
    @else
        <input type="hidden" name="establishment_id" value="{{ Session::get('establishment_id') }}">
    @endif
    @if ($role && in_array($role->name, ['superuser']))
    <div class="w-full">
    @else
    <div class="sm:col-span-2">
    @endif
        <label for="category_id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoria</label>
        <select id="category_id" name="category_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option selected="">Selecione</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} {{ isset($exercise) && $exercise->category_id == $category->id ? 'selected' : ''}}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
        <input type="text" name="name" id="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                value="{{ $exercise->name ?? old('name') }}"
                placeholder="Nome">
    </div>
    <div class="sm:col-span-2">
        <label for="description"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
        <textarea id="description" rows="8"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Descrição" name="description">{{ $exercise->description ?? old('description') }}</textarea>
    </div>
    <div class="sm:col-span-2">
        <label for="exercise_picture"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagem do exercício</label>
        @if (isset($exercise) && $exercise->exercise_picture)
            <img src="{{ asset('storage/' . $exercise->exercise_picture) }}" class="w-32 h-32">
            <a href="#" onclick="removeExercisePicture()" class="text-red-500">Remover imagem</a>
        @endif
        <div class="@if (isset($exercise) && $exercise->exercise_picture) sm:col-span-2 hidden @endif">
            <input name="exercise_picture" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="exercise_picture_help" id="exercise_picture" type="file">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="exercise_picture_help">SVG, PNG, JPG or GIF (MAX. 800x400px).</p>
        </div>
    </div>
    <div class="sm:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" value="1" class="sr-only peer" name="active" {{ old('active', $exercise->active ?? false) ? 'checked' : '' }}>
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
        </label>
    </div>
</div>
<div class="mt-4 flex justify-end">
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
        {{ isset($exercise) ? 'Atualizar Exercício' : 'Criar Exercício' }}
    </button>
    <a href="{{ route('admin.exercises.index') }}"
    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</a>
</div>
@push('footer')
    <script>
        function removeExercisePicture() {
            
        }
    </script>
@endpush
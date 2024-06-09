@csrf
<div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
    @if (isset($workout))
    <h2 class="text-4xl font-extrabold dark:text-white">
        <small class="font-semibold text-gray-500 dark:text-gray-400">{{$workout->establishment->name}}</small>
        <input type="hidden" name="establishment_id" value="{{ $workout->establishment->id }}">
    </h2>
    <h2 class="text-4xl font-extrabold dark:text-white">
        <small class="font-semibold text-gray-500 dark:text-gray-400">{{$workout->user->name}}</small>
        <input type="hidden" name="user_id" value="{{ $workout->user->id }}">
    </h2>
    <h2 class="text-4xl font-extrabold dark:text-white">
        <small class="font-semibold text-gray-500 dark:text-gray-400">{{$workout->student->name}}</small>
        <input type="hidden" name="student_id" value="{{ $workout->student->id }}">
    </h2>
    <h2 class="text-4xl font-extrabold dark:text-white">
        <small class="font-semibold text-gray-500 dark:text-gray-400">{{$workout->exercise->name}}</small>
        <input type="hidden" name="exercise_id" value="{{ $workout->exercise->id }}">
    </h2>
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
    <div class="w-full">
        <label for="user_id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Instrutor</label>
        <select id="user_id" name="user_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option selected="">Selecione</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="w-full">
        <label for="student_id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Aluno</label>
        <select id="student_id" name="student_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option selected="">Selecione</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                    {{ $student->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="w-full">
        <label for="exercise_id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Treino</label>
        <select id="exercise_id" name="exercise_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option selected="">Selecione</option>
            @foreach ($exercises as $exercise)
                <option value="{{ $exercise->id }}" {{ old('exercise_id') == $exercise->id ? 'selected' : '' }}>
                    {{ $exercise->name }}
                </option>
            @endforeach
        </select>
    </div>
    @endif
    <div class="flex gap-4 sm:col-span-2">
        <div class="w-full">
            <label for="sets"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Séries</label>
            <input type="number" name="sets" id="sets"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    value="{{ $workout->sets ?? old('sets') }}"
                    placeholder="Séries">
        </div> 
        <div class="w-full">
            <label for="repetitions"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Repetições</label>
            <input type="number" name="repetitions" id="repetitions"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    value="{{ $workout->repetitions ?? old('repetitions') }}"
                    placeholder="Repetições">
        </div>
        <div class="w-full">
            <label for="rest_time"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempo de descanso</label>
            <input type="number" name="rest_time" id="rest_time"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    value="{{ $workout->rest_time ?? old('rest_time') }}"
                    placeholder="Tempo de descanso">
        </div>
    </div>
    <div class="sm:col-span-2">
        <label for="notes"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anotações</label>
        <textarea id="notes" rows="8"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Descrição" name="notes">{{ $workout->notes ?? old('notes') }}</textarea>
    </div>
    <div class="sm:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" value="1" class="sr-only peer" name="active" {{ old('active', $workout->active ?? false) ? 'checked' : '' }}>
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
        </label>
    </div>
    <input type="hidden" name="order" value="{{ $workout->order ?? 1 }}">
</div>
<div class="mt-4 flex justify-end">
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
        {{ isset($workout) ? 'Atualizar Treino' : 'Criar Treino' }}
    </button>
    <a href="{{ route('admin.workouts.index') }}"
        class="ml-4 mt-2 text-gray-500 hover:text-gray-600">Cancelar</a>
</div>
@csrf
<div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
    @if (isset($class_schedule))
    <h2 class="text-4xl font-extrabold dark:text-white">
        <small class="font-semibold text-gray-500 dark:text-gray-400">{{$class_schedule->establishment->name}}</small>
        <input type="hidden" name="establishment_id" value="{{ $class_schedule->establishment->id }}">
    </h2>
    <h2 class="text-4xl font-extrabold dark:text-white">
        <small class="font-semibold text-gray-500 dark:text-gray-400">{{$class_schedule->modality->name}}</small>
        <input type="hidden" name="modality_id" value="{{ $class_schedule->modality->id }}">
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
        <label for="modality_id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Modalidade</label>
        <select id="modality_id" name="modality_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option selected="">Selecione</option>
            @foreach ($modalities as $modality)
                <option value="{{ $modality->id }}" {{ old('modality_id') == $modality->id ? 'selected' : '' }}>
                    {{ $modality->name }}
                </option>
            @endforeach
        </select>
    </div>
    @endif
    <div class="sm:col-span-2">
        <label for="description"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
        <input type="text" name="description" id="description"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                value="{{ $class_schedule->description ?? old('description') }}"
                placeholder="Descricão">
    </div>
    <div class="sm:col-span-2">
        <label for="class_date"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data</label>
        <input type="text" name="class_date" id="class_date"
                class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                value="{{ $class_schedule->class_date ?? old('class_date') }}"
                placeholder="Data">
    </div>
    <div class="w-full">
        <label for="start_time"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora início</label>
        <input type="text" name="start_time" id="start_time"
                class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                value="{{ $class_schedule->start_time ?? old('start_time') }}"
                placeholder="Hora início">
    </div>
    <div class="w-full">
        <label for="end_time"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora fim</label>
        <input type="text" name="end_time" id="end_time"
                class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                value="{{ $class_schedule->end_time ?? old('end_time') }}"
                placeholder="Hora fim">
    </div>
</div>
<div class="mt-4 flex justify-end">
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
        {{ isset($class_schedule) ? 'Atualizar Aula' : 'Criar Aula' }}
    </button>
    <a href="{{ route('admin.class_schedules.index') }}"
        class="ml-4 mt-2 text-gray-500 hover:text-gray-600">Cancelar</a>
</div>
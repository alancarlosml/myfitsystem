<div class="text-sm font-medium text-center mt-7 text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
        <li class="me-2">
            <a href="{{ route('admin.establishments.view', $establishment->id) }}" class="inline-block p-4 border-b-2 rounded-t-lg @if(Request::route()->getName() == 'admin.establishments.view') active text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500 @else border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 @endif">Perfil</a>
        </li>
        <li class="me-2">
            <a href="{{ route('admin.establishments.students', $establishment->id) }}" class="inline-block p-4 border-b-2 rounded-t-lg @if(Request::route()->getName() == 'admin.establishments.students') active text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500 @else border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 @endif">Alunos</a>
        </li>
        <li class="me-2">
            <a href="{{ route('admin.establishments.users', $establishment->id) }}" class="inline-block p-4 border-b-2 rounded-t-lg @if(Request::route()->getName() == 'admin.establishments.users') active text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500 @else border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 @endif">Colaboradores</a>
        </li>
        <li class="me-2">
            <a href="{{ route('admin.establishments.contracts', $establishment->id) }}" class="inline-block p-4 border-b-2 rounded-t-lg @if(Request::route()->getName() == 'admin.establishments.contracts') active text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500 @else border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 @endif">Contratos</a>
        </li>
        {{-- <li class="me-2">
            <a href="#" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Cadastros</a>
        </li>
        <li class="me-2">
            <a href="{{route('admin.establishments.exercises', $establishment->id)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Exercícios</a>
        </li>
        <li class="me-2">
            <a href="{{route('admin.establishments.contracts', $establishment->id)}}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Contratos</a>
        </li> --}}
    </ul>
</div>

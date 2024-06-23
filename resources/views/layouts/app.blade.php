<!doctype html>
<html x-data="{ darkMode: localStorage.getItem('dark') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
      x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body>
    <div class="flex gap-8 bg-white dark:bg-gray-900 h-screen">
        <x-sidebar class="min-w-fit flex-grow-0 flex-shrink-0 hidden md:block" />
        <main class="mt-24 px-8 sm:ml-64 flex-grow">
            {{-- <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                 role="alert">
                <span class="font-medium">{{\App\Models\Establishment::find(Session::get('establishment_id'))->name}}</span>
            </div> --}}
            {{ $slot }}
            <x-footer />
        </main>
    </div>
    @stack('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.0/flowbite.min.js"></script>
</body>

</html>

@props(['name' => null, 'messages' => null])

@php
    $errorMessages = $messages ?? ($name ? $errors->get($name) : []);
@endphp

@if (!empty($errorMessages))
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $errorMessages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif

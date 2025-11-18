@props(['title', 'icon' => null, 'color' => 'blue', 'trend' => null, 'href' => null])

@php
    $colors = [
        'blue' => ['bg' => 'from-blue-500 to-blue-600', 'icon' => 'bg-blue-100 dark:bg-blue-900', 'iconColor' => 'text-blue-600 dark:text-blue-400'],
        'green' => ['bg' => 'from-green-500 to-green-600', 'icon' => 'bg-green-100 dark:bg-green-900', 'iconColor' => 'text-green-600 dark:text-green-400'],
        'purple' => ['bg' => 'from-purple-500 to-purple-600', 'icon' => 'bg-purple-100 dark:bg-purple-900', 'iconColor' => 'text-purple-600 dark:text-purple-400'],
        'orange' => ['bg' => 'from-orange-500 to-orange-600', 'icon' => 'bg-orange-100 dark:bg-orange-900', 'iconColor' => 'text-orange-600 dark:text-orange-400'],
        'red' => ['bg' => 'from-red-500 to-red-600', 'icon' => 'bg-red-100 dark:bg-red-900', 'iconColor' => 'text-red-600 dark:text-red-400'],
        'indigo' => ['bg' => 'from-indigo-500 to-indigo-600', 'icon' => 'bg-indigo-100 dark:bg-indigo-900', 'iconColor' => 'text-indigo-600 dark:text-indigo-400'],
    ];
    $colorScheme = $colors[$color] ?? $colors['blue'];
@endphp

@if($href)
    <a href="{{ $href }}" class="block group">
@endif
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 {{ $href ? 'cursor-pointer' : '' }}">
            <div class="bg-gradient-to-r {{ $colorScheme['bg'] }} p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-medium text-white/80 uppercase tracking-wide">{{ $title }}</p>
                        <h3 class="mt-2 text-3xl font-bold text-white">{{ $slot }}</h3>
                        @if($trend)
                            <div class="mt-2 flex items-center text-sm">
                                @if($trend['direction'] === 'up')
                                    <svg class="w-4 h-4 mr-1 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-green-200 font-medium">+{{ $trend['value'] }}</span>
                                @else
                                    <svg class="w-4 h-4 mr-1 text-red-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-red-200 font-medium">{{ $trend['value'] }}</span>
                                @endif
                                <span class="ml-1 text-white/70 text-xs">{{ $trend['label'] ?? '' }}</span>
                            </div>
                        @endif
                    </div>
                    @if($icon)
                        <div class="{{ $colorScheme['icon'] }} rounded-lg p-3 ml-4">
                            <div class="{{ $colorScheme['iconColor'] }}">
                                {!! $icon !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if($href)
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors">
                        Ver detalhes
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </p>
                </div>
            @endif
        </div>
@if($href)
    </a>
@endif

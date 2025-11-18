@extends('layouts.app')

@section('content')
<div class="py-6">
    <x-header title="Notificações" />
    
    <div class="mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Header Actions -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Todas as Notificações
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span id="unread-count">{{ $unreadCount }}</span> não lida(s)
                    </p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('student.notifications.mark_all_read') }}" method="POST" id="mark-all-read-form">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                            Marcar todas como lidas
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($notifications as $notification)
                    <div class="notification-item px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors {{ !$notification->read ? 'bg-blue-50 dark:bg-gray-900' : '' }}"
                         data-notification-id="{{ $notification->id }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white
                                    {{ $notification->type === 'success' ? 'bg-green-500' : '' }}
                                    {{ $notification->type === 'warning' ? 'bg-yellow-500' : '' }}
                                    {{ $notification->type === 'error' ? 'bg-red-500' : '' }}
                                    {{ $notification->type === 'info' || !in_array($notification->type, ['success', 'warning', 'error']) ? 'bg-blue-500' : '' }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $notification->title }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $notification->message }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex items-center gap-2">
                                        @if(!$notification->read)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                Nova
                                            </span>
                                        @endif
                                        <div class="flex items-center gap-2">
                                            @if(!$notification->read)
                                                <form action="{{ route('student.notifications.mark_read', $notification->id) }}" 
                                                      method="POST" 
                                                      class="mark-read-form inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                                            title="Marcar como lida">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('student.notifications.destroy', $notification->id) }}" 
                                                  method="POST" 
                                                  class="delete-notification-form inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir esta notificação?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                        title="Excluir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @if($notification->action_url)
                                    <div class="mt-2">
                                        <a href="{{ $notification->action_url }}" 
                                           class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center">
                                            Ver detalhes
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma notificação</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Você não tem notificações no momento.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400">
                            Mostrando {{ $notifications->firstItem() ?? 0 }} até {{ $notifications->lastItem() ?? 0 }} de {{ $notifications->total() }} notificações
                        </div>
                        <div class="flex gap-2">
                            @if($notifications->onFirstPage())
                                <span class="px-3 py-1 text-sm text-gray-400 dark:text-gray-600 cursor-not-allowed">Anterior</span>
                            @else
                                <a href="{{ $notifications->previousPageUrl() }}" 
                                   class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Anterior
                                </a>
                            @endif
                            
                            @if($notifications->hasMorePages())
                                <a href="{{ $notifications->nextPageUrl() }}" 
                                   class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Próxima
                                </a>
                            @else
                                <span class="px-3 py-1 text-sm text-gray-400 dark:text-gray-600 cursor-not-allowed">Próxima</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle mark as read
    document.querySelectorAll('.mark-read-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const notificationItem = this.closest('.notification-item');
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notificationItem.classList.remove('bg-blue-50', 'dark:bg-gray-900');
                    const badge = notificationItem.querySelector('.bg-blue-100');
                    if (badge) {
                        badge.remove();
                    }
                    this.remove();
                    
                    // Update unread count
                    const unreadCountEl = document.getElementById('unread-count');
                    if (unreadCountEl) {
                        const current = parseInt(unreadCountEl.textContent) || 0;
                        unreadCountEl.textContent = Math.max(0, current - 1);
                    }
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
    });

    // Handle delete
    document.querySelectorAll('.delete-notification-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Tem certeza que deseja excluir esta notificação?')) {
                return;
            }
            
            const formData = new FormData(this);
            const notificationItem = this.closest('.notification-item');
            
            fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notificationItem.style.transition = 'opacity 0.3s';
                    notificationItem.style.opacity = '0';
                    setTimeout(() => {
                        notificationItem.remove();
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
    });

    // Handle mark all as read
    const markAllReadForm = document.getElementById('mark-all-read-form');
    if (markAllReadForm) {
        markAllReadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove all badges and update items
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('bg-blue-50', 'dark:bg-gray-900');
                        const badge = item.querySelector('.bg-blue-100');
                        if (badge) {
                            badge.remove();
                        }
                        const markReadBtn = item.querySelector('.mark-read-form');
                        if (markReadBtn) {
                            markReadBtn.remove();
                        }
                    });
                    
                    // Update unread count
                    const unreadCountEl = document.getElementById('unread-count');
                    if (unreadCountEl) {
                        unreadCountEl.textContent = '0';
                    }
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
    }
});
</script>
@endpush
@endsection


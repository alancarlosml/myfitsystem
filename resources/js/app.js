import './bootstrap';
import './masks';
import Alpine from 'alpinejs';

// Inicializar Alpine.js
window.Alpine = Alpine;

// Notification System - Registrado ANTES do Alpine.start()
Alpine.data('notificationDropdown', (guard) => ({
    open: false,
    notifications: [],
    unreadCount: 0,
    loading: true,
    pollInterval: null,
    guard: guard || 'user',
    
    init() {
        this.fetchNotifications();
        this.startPolling();
        
        // Cleanup polling when page is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.startPolling();
            }
        });
    },
    
    fetchNotifications() {
        const route = this.guard === 'student' 
            ? '/app/notificacoes/nao-lidas' 
            : '/gestao/notificacoes/nao-lidas';
        
        if (typeof window.axios === 'undefined') {
            console.error('Axios não está disponível');
            this.loading = false;
            return;
        }
        
        const axios = window.axios;
        
        axios.get(route)
            .then(response => {
                this.notifications = response.data.notifications || [];
                this.unreadCount = response.data.count || 0;
                this.loading = false;
            })
            .catch(error => {
                console.error('Erro ao buscar notificações:', error);
                this.loading = false;
            });
    },
    
    startPolling() {
        // Poll every 30 seconds
        this.pollInterval = setInterval(() => {
            if (!document.hidden) {
                this.fetchNotifications();
            }
        }, 30000);
    },
    
    stopPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    },
    
    async markAsRead(notificationId, event) {
        if (event) {
            event.preventDefault();
        }
        
        if (typeof window.axios === 'undefined') {
            console.error('Axios não está disponível');
            return;
        }
        
        const axios = window.axios;
        const route = this.guard === 'student'
            ? `/app/notificacoes/${notificationId}/marcar-lida`
            : `/gestao/notificacoes/${notificationId}/marcar-lida`;
        
        try {
            await axios.post(route, {}, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            // Update local state
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification) {
                notification.read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }
            
            // If action_url exists, navigate to it
            if (notification && notification.action_url) {
                window.location.href = notification.action_url;
            }
        } catch (error) {
            console.error('Erro ao marcar notificação como lida:', error);
        }
    },
    
    formatTime(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) return 'há alguns segundos';
        if (diffMins < 60) return `há ${diffMins} minuto${diffMins > 1 ? 's' : ''}`;
        if (diffHours < 24) return `há ${diffHours} hora${diffHours > 1 ? 's' : ''}`;
        if (diffDays < 7) return `há ${diffDays} dia${diffDays > 1 ? 's' : ''}`;
        
        return date.toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
}));

// Agora inicia o Alpine
Alpine.start();

// Force cache reload for development debugging
if (localStorage.getItem('force-cache-reload')) {
    localStorage.removeItem('force-cache-reload');
    window.location.reload(true);
}

// PWA Service Worker Registration - Versão Simplificada para Desenvolvimento
if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    console.log('Ambiente seguro detectado - registrando Service Worker...');

    navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('SW registrado com sucesso:', registration.scope);

            // Listen for updates to the service worker
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                if (newWorker) {
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            console.log('Novo Service Worker instalado - nova versão disponível!');
                        }
                    });
                }
            });

            console.log('✅ Service Worker ativo e funcionando');
        })
        .catch(error => {
            console.warn('⚠️ Service Worker não suportado ou falhou:', error);
        });
} else {
    // Em desenvolvimento (HTTP), skip SW para evitar problemas
    if (window.location.protocol !== 'https:') {
        console.log('📋 Ambiente de desenvolvimento detectado - Service Worker desabilitado');
        console.log('💡 Para habilitar PWA features, use HTTPS em produção');
    }
}

// PWA Install Prompt - Apenas em produção
if (window.location.protocol === 'https:') {
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        console.log('🎯 Instalar PWA disponível!');
        console.log('💡 Em produção, você pode mostrar um prompt customizado para instalação');

        // Para desenvolvimento, apenas log - em produção pode mostrar modal elegante
        // deferredPrompt.prompt();
        // ...
    });
}

// Handle network status changes
window.addEventListener('online', () => {
    console.log('Conexão online restabelecida');
    // Sync pending data if any
});

window.addEventListener('offline', () => {
    console.log('Sem conexão - modo offline ativado');
    // Show offline indicator
    showOfflineIndicator();
});

function showOfflineIndicator() {
    const indicator = document.createElement('div');
    indicator.id = 'offline-indicator';
    indicator.className = 'fixed top-0 left-0 right-0 bg-orange-500 text-white px-4 py-2 text-center text-sm font-medium z-50';
    indicator.textContent = '🔌 Modo offline ativado - Algumas funcionalidades podem não funcionar.';
    document.body.appendChild(indicator);

    setTimeout(() => {
        if (indicator.parentNode) {
            indicator.parentNode.removeChild(indicator);
        }
    }, 5000);
}

// Handle app visibility for background sync
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        console.log('App voltou ao foco');
        // Sync data if needed
    }
});

// Performance monitoring
if ('performance' in window && 'getEntriesByType' in performance) {
    window.addEventListener('load', () => {
        const navigationEntries = performance.getEntriesByType('navigation');
        if (navigationEntries.length > 0) {
            const entry = navigationEntries[0];
            console.log('Tempo de carregamento da página:', entry.loadEventEnd - entry.fetchStart, 'ms');
        }
    });
}


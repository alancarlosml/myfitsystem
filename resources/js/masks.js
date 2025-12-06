/**
 * Sistema global de máscaras e date pickers
 * Padroniza máscaras de CPF, CNPJ, telefone, dinheiro e date pickers em todo o sistema
 */

// Aguarda o DOM estar pronto
document.addEventListener('DOMContentLoaded', function() {
    initializeMasks();
    initializeDatePickers();
    
    // Observa mudanças no DOM para elementos dinâmicos (modais, etc)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        // Reinicializa máscaras e date pickers em novos elementos
                        if (node.querySelectorAll) {
                            const hasMaskElements = node.querySelectorAll('.mask-cpf, .mask-cnpj, .mask-phone, .mask-money, .flatpickr-date').length > 0;
                            if (hasMaskElements) {
                                setTimeout(function() {
                                    reinitializeMasks(node);
                                }, 100);
                            }
                        }
                    }
                });
            }
        });
    });
    
    // Observa mudanças no body
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

/**
 * Inicializa todas as máscaras do sistema
 */
function initializeMasks() {
    // Máscara de CPF
    const cpfInputs = document.querySelectorAll('.mask-cpf, input[name="cpf"]');
    cpfInputs.forEach(input => {
        if (!input.dataset.maskInitialized) {
            applyCpfMask(input);
            input.dataset.maskInitialized = 'true';
        }
    });

    // Máscara de CNPJ
    const cnpjInputs = document.querySelectorAll('.mask-cnpj, input[name="cnpj"]');
    cnpjInputs.forEach(input => {
        if (!input.dataset.maskInitialized) {
            applyCnpjMask(input);
            input.dataset.maskInitialized = 'true';
        }
    });

    // Máscara de telefone
    const phoneInputs = document.querySelectorAll('.mask-phone, input[name="phone"]');
    phoneInputs.forEach(input => {
        if (!input.dataset.maskInitialized) {
            applyPhoneMask(input);
            input.dataset.maskInitialized = 'true';
        }
    });

    // Máscara de dinheiro
    const moneyInputs = document.querySelectorAll('.mask-money, input[name="amount"]');
    moneyInputs.forEach(input => {
        if (!input.dataset.maskInitialized) {
            applyMoneyMask(input);
            input.dataset.maskInitialized = 'true';
        }
    });
}

/**
 * Aplica máscara de CPF
 */
function applyCpfMask(input) {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        }
    });
}

/**
 * Aplica máscara de CNPJ
 */
function applyCnpjMask(input) {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 14) {
            value = value.replace(/(\d{2})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1/$2');
            value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        }
    });
}

/**
 * Aplica máscara de telefone
 */
function applyPhoneMask(input) {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        }
    });
}

/**
 * Aplica máscara de dinheiro (R$)
 */
function applyMoneyMask(input) {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (value / 100).toFixed(2) + '';
        value = value.replace('.', ',');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        e.target.value = 'R$ ' + value;
    });

    // Formata valor inicial se existir
    if (input.value && input.value !== '') {
        const numericValue = input.value.replace(/\D/g, '');
        if (numericValue) {
            const formatted = (numericValue / 100).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = 'R$ ' + formatted;
        }
    }
}

/**
 * Inicializa todos os date pickers do sistema
 */
function initializeDatePickers() {
    // Verifica se flatpickr está disponível
    if (typeof flatpickr === 'undefined') {
        console.warn('Flatpickr não está disponível. Certifique-se de incluir o script.');
        return;
    }

    // Date pickers
    const dateInputs = document.querySelectorAll('.flatpickr-date, input[type="text"][name*="date"]:not(.flatpickr-time), input[type="text"][name*="birthdate"]');
    dateInputs.forEach(input => {
        // Pula inputs que já foram inicializados ou que são readonly (como end_date calculado)
        if (input.dataset.flatpickrInitialized || input.readOnly) {
            return;
        }

        const config = {
            dateFormat: "d/m/Y",
            locale: "pt",
            allowInput: true,
            altInput: false
        };

        // Configurações especiais para data de nascimento
        if (input.name === 'birthdate' || input.id === 'birthdate') {
            config.maxDate = "today";
        }

        // Configurações especiais para data de aula (não pode ser no passado)
        if (input.name === 'class_date' || input.id === 'class_date') {
            config.minDate = "today";
        }

        flatpickr(input, config);
        input.dataset.flatpickrInitialized = 'true';
    });

    // Time pickers
    const timeInputs = document.querySelectorAll('.flatpickr-time, input[type="text"][name*="time"]:not(.flatpickr-date)');
    timeInputs.forEach(input => {
        // Pula inputs que já foram inicializados
        if (input.dataset.flatpickrInitialized) {
            return;
        }

        const config = {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "pt",
            allowInput: true
        };

        flatpickr(input, config);
        input.dataset.flatpickrInitialized = 'true';
    });
}

/**
 * Função para recalcular máscaras em elementos dinâmicos (útil para modais)
 */
function reinitializeMasks(container = document) {
    // Remove flags de inicialização dos elementos dentro do container
    const inputs = container.querySelectorAll('[data-mask-initialized]');
    inputs.forEach(input => {
        delete input.dataset.maskInitialized;
    });

    // Reinicializa máscaras
    initializeMasks();
    initializeDatePickers();
}

// Exporta funções para uso global
window.MaskSystem = {
    initialize: initializeMasks,
    initializeDatePickers: initializeDatePickers,
    reinitialize: reinitializeMasks
};


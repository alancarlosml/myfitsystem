<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Adicionar Aluno</h1>
                        <p class="mt-1 text-purple-100">Preencha os dados para cadastrar um novo aluno</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-error />

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @include('admin.students.partials.form')
                </form>
            </div>
        </div>
    </section>

    @push('head')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('footer')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Date picker para data de nascimento
                flatpickr("#birthdate", {
                    dateFormat: "d/m/Y",
                    locale: "pt",
                    allowInput: true,
                    altInput: false,
                    maxDate: "today"
                });

                // Máscara de CPF
                if (typeof jQuery !== 'undefined' && jQuery.fn.mask) {
                    jQuery('#cpf').mask('000.000.000-00');
                } else {
                    // Fallback usando vanilla JS
                    const cpfInput = document.getElementById('cpf');
                    if (cpfInput) {
                        cpfInput.addEventListener('input', function(e) {
                            let value = e.target.value.replace(/\D/g, '');
                            if (value.length <= 11) {
                                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                                e.target.value = value;
                            }
                        });
                    }
                }

                // Máscara de telefone
                const phoneInput = document.getElementById('phone');
                if (phoneInput) {
                    phoneInput.addEventListener('input', function(e) {
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
            });
        </script>
    @endpush
</x-app-layout>

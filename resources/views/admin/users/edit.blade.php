<x-app-layout>
    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Editar Colaborador</h1>
                        <p class="mt-1 text-cyan-100">Atualize as informações do colaborador</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-error />

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @method('put')
                    @include('admin.users.partials.form')
                </form>
            </div>
        </div>
    </section>

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Máscara de CPF
                if (typeof jQuery !== 'undefined' && jQuery.fn.mask) {
                    jQuery('#cpf').mask('000.000.000-00');
                } else {
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

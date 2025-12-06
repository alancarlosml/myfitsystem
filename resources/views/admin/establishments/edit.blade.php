<x-app-layout>
    <x-header>
        <x-slot:title>Estabelecimentos</x-slot:title>
    </x-header>

    <!-- Header Moderno com Gradiente -->
    <div class="bg-gradient-to-r from-amber-600 via-yellow-600 to-orange-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Editar Estabelecimento</h1>
                        <p class="mt-1 text-amber-100">Atualize as informações do estabelecimento</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert-error />

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('admin.establishments.update', $establishment->id) }}" method="POST">
                    @method('put')
                    @include('admin.establishments.partials.form')
                </form>
            </div>
        </div>
    </section>

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Máscara de CNPJ
                if (typeof jQuery !== 'undefined' && jQuery.fn.mask) {
                    jQuery('#cnpj').mask('00.000.000/0000-00');
                } else {
                    const cnpjInput = document.getElementById('cnpj');
                    if (cnpjInput) {
                        cnpjInput.addEventListener('input', function(e) {
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

<x-guest-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img src="{{ asset('img/logo.png') }}" class="h-12" alt="MyFit System">
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-lg xl:p-0 dark:bg-gray-800 dark:border-gray-700" x-data="loginForm">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Gestão - Login
                    </h1>
                    <div x-show="errorMessage && errorMessage.length > 0" x-transition class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <span x-text="errorMessage"></span>
                    </div>
                    <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('user.login') }}">
                        @csrf
                        <div>
                            <label for="email"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Seu email
                            </label>
                            <input type="email" name="email" id="email" x-model="email"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="name@company.com">
                        </div>
                        <div>
                            <label for="password"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha</label>
                            <input type="password" name="password" id="password" x-model="password" placeholder="••••••••"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   minlength="6">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" name="remember" aria-describedby="remember" type="checkbox"
                                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Lembrar</label>
                                </div>
                            </div>
                            <a href="#"
                               class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                                Esqueceu sua senha?
                            </a>
                        </div>
                        <button type="submit" x-bind:disabled="isSubmitting"
                                class="w-full bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Entrar</span>
                            <span x-show="isSubmitting" x-cloak>Entrando...</span>
                        </button>
                    </form>
                    @if ($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const formDiv = document.querySelector('div[x-data="loginForm"]');
                                formDiv.__x.updateDataFromString('errorMessage', @json($errors->first()));
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loginForm', () => ({
                email: '',
                password: '',
                errorMessage: '',
                isSubmitting: false,

                validateEmail() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(this.email);
                },

                onSubmit() {
                    this.errorMessage = '';
                    if (!this.validateEmail()) {
                        this.errorMessage = 'Por favor, insira um e-mail válido.';
                        return false;
                    }
                    if (this.password.length < 6) {
                        this.errorMessage = 'A senha deve ter pelo menos 6 caracteres.';
                        return false;
                    }
                    this.isSubmitting = true;
                    return true;
                }
            }));
        });
    </script>
</x-guest-layout>

<x-guest-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img src="{{ asset('img/logo.png') }}" class="h-12" alt="MyFit System">
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-lg xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Alunos - Login
                    </h1>
                    <div id="errorDiv" class="hidden flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                        <svg class="flex-shrink-0 inline w-5 h-5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Erro</span>
                        <div>
                            <span class="font-medium">Erro:</span>
                            <span id="errorMessage" class="ml-1"></span>
                        </div>
                    </div>
                    <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('student.login') }}">
                        @csrf
                        <div>
                            <label for="email"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Seu email
                            </label>
                            <input type="email" name="email" id="email" autocomplete="off"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="name@company.com" required>
                        </div>
                        <div>
                            <label for="password"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha</label>
                            <input type="password" name="password" id="password" autocomplete="off" placeholder="••••••••"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   required>
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
                            <a href="{{ route('student.password.request') }}"
                               class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                                Esqueceu sua senha?
                            </a>
                        </div>
                        <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Entrar
                        </button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Não tem uma conta ainda? <a href="{{ route('student.register') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Criar conta</a>
                        </p>
                    </form>
                    @if (session('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const errorDiv = document.getElementById('errorDiv');
                                const errorSpan = document.getElementById('errorMessage');
                                errorSpan.textContent = "{{ session('error') }}";
                                errorDiv.classList.remove('hidden');
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </section>

</x-guest-layout>

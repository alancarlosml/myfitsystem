<x-guest-layout>
    <section class="h-full flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <div class="flex flex-col items-center mb-6">
                    <a href="/" class="flex items-center mb-4 text-2xl font-semibold text-gray-900 dark:text-white">
                        <img src="{{ asset('img/logo.png') }}" class="h-16" alt="MyFit System">
                    </a>
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white text-center">
                        Bem-vindo de volta!
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Acesse sua conta de aluno
                    </p>
                </div>

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

                <form class="space-y-5" method="POST" action="{{ route('student.login') }}">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seu email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input type="email" name="email" id="email" autocomplete="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="seu@email.com" required>
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" name="password" id="password" autocomplete="current-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" name="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-500 dark:text-gray-300">Lembrar</label>
                            </div>
                        </div>
                        <a href="{{ route('student.password.request') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Esqueceu?</a>
                    </div>
                    <button type="submit" class="w-full text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow-md transition-all transform active:scale-95">
                        Entrar
                    </button>
                    <p class="text-sm font-light text-center text-gray-500 dark:text-gray-400">
                        Novo por aqui? <a href="{{ route('student.register') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Crie sua conta</a>
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
    </section>
</x-guest-layout>

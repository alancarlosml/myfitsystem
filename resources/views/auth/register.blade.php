<x-guest-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img src="{{ asset('img/logo.png') }}" class="h-12" alt="MyFit System">
            </a>
            <div
                 class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Crie sua conta
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="{{ route('student.register') }}" method="POST">
                        <div>
                            <label for="email"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seu
                                email</label>
                            <input type="email" name="email" id="email"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="name@company.com" required="">
                        </div>
                        <div>
                            <label for="password"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha</label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   required="">
                        </div>
                        <div>
                            <label for="confirm-password"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar
                                senha</label>
                            <input type="confirm-password" name="confirm-password" id="confirm-password"
                                   placeholder="••••••••"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   required="">
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" aria-describedby="terms" type="checkbox"
                                       class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                                       required="">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-light text-gray-500 dark:text-gray-300">Eu aceito os
                                    <a class="font-medium text-primary-600 hover:underline dark:text-primary-500"
                                       href="#">Termos e Condições</a></label>
                            </div>
                        </div>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Criar uma conta</button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Já tem uma conta? <a href="{{ route('student.login') }}"
                               class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>

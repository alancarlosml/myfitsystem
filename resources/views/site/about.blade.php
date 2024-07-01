<x-site-layout>
    <!-- Start block -->
    <section class="bg-white dark:bg-gray-900">
        <div class="grid max-w-screen-xl px-4 pt-24 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-40">
            <!-- Título e Texto -->
            <div class="lg:col-span-12">
                <h2 class="w-full mb-4 text-3xl font-extrabold leading-none tracking-tight md:text-4xl xl:text-4xl dark:text-white">
                    Entre em Contato Conosco
                </h2>
                <p class="w-full mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                    Quer saber mais sobre como o MyFit System pode transformar a gestão da sua academia ou do seu trabalho como personal trainer? Preencha o formulário abaixo e entraremos em contato com você o mais breve possível. Aproveite todas as vantagens de nossa plataforma inovadora!
                </p>
            </div>
            <!-- Formulário de Contato -->
            <div class="lg:col-span-12">
                <div class="bg-white dark:bg-gray-800">
                    <form action="#" method="POST" class="space-y-6">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                            <input type="text" id="name" name="name" class="block w-full p-3 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:text-sm focus:ring-red-600 focus:border-red-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Seu nome" required>
                        </div>
                        <!-- Email -->
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" id="email" name="email" class="block w-full p-3 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:text-sm focus:ring-red-600 focus:border-red-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="nome@exemplo.com" required>
                        </div>
                        <!-- Assunto -->
                        <div>
                            <label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assunto</label>
                            <input type="text" id="subject" name="subject" class="block w-full p-3 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:text-sm focus:ring-red-600 focus:border-red-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Assunto da mensagem" required>
                        </div>
                        <!-- Mensagem -->
                        <div>
                            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mensagem</label>
                            <textarea id="message" name="message" rows="4" class="block w-full p-3 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:text-sm focus:ring-red-600 focus:border-red-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Sua mensagem" required></textarea>
                        </div>
                        <!-- Botão Enviar -->
                        <button type="submit" class="w-full px-5 py-3 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-200 font-medium rounded-lg text-sm dark:focus:ring-red-900">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End block -->
</x-site-layout>
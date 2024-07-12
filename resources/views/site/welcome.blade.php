<x-site-layout>
    <!-- Start block -->
    <section class="bg-white dark:bg-gray-900">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1
                    class="max-w-2xl mb-4 text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">
                    Transformando a Gestão de Academias & Personal Trainers</h1>
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                    MyFit System é uma plataforma inovadora para o controle e gestão de academias, crossfits e personal
                    trainers. Utilize nossa tecnologia para otimizar suas operações e melhorar a experiência dos seus
                    alunos.
                </p>
                <div class="space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('saiba-mais') }}"
                       class="inline-flex items-center justify-center w-full px-5 py-3 text-sm font-medium text-center text-white bg-red-700 rounded-lg sm:w-auto hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                        Saiba Mais
                    </a>
                </div>
            </div>
            <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                <img src="{{ asset('img/hero.png') }}" alt="hero image">
            </div>
        </div>
    </section>
    <!-- End block -->
    <!-- Start block -->
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 pb-8 mx-auto lg:pb-16">
            <div
                 class="grid grid-cols-2 gap-8 text-gray-500 sm:gap-12 sm:grid-cols-3 lg:grid-cols-6 dark:text-gray-400">
                <a href="#" class="flex justify-center lg:justify-center">
                    <img src="{{ asset('img/1.png') }}" class="h-24 hover:text-gray-900 dark:hover:text-white" alt="Parceiro 1">
                </a>
                <a href="#" class="flex justify-center lg:justify-center">
                    <img src="{{ asset('img/2.png') }}" class="h-24 hover:text-gray-900 dark:hover:text-white" alt="Parceiro 2">
                </a>
                <a href="#" class="flex justify-center lg:justify-center">
                    <img src="{{ asset('img/3.png') }}" class="h-24 hover:text-gray-900 dark:hover:text-white" alt="Parceiro 3">
                </a>
                <a href="#" class="flex justify-center lg:justify-center">
                    <img src="{{ asset('img/4.png') }}" class="h-24 hover:text-gray-900 dark:hover:text-white" alt="Parceiro 4">
                </a>
                <a href="#" class="flex justify-center lg:justify-center">
                    <img src="{{ asset('img/5.png') }}" class="h-24 hover:text-gray-900 dark:hover:text-white" alt="Parceiro 5">
                </a>
                <a href="#" class="flex justify-center lg:justify-center">
                    <img src="{{ asset('img/6.png') }}" class="h-24 hover:text-gray-900 dark:hover:text-white" alt="Parceiro 6">
                </a>
            </div>
        </div>
    </section>
    <!-- End block -->
    <!-- Start block -->
    <section class="bg-gray-50 dark:bg-gray-800">
        <div class="max-w-screen-xl px-4 py-8 mx-auto space-y-12 lg:space-y-20 lg:py-24 lg:px-6">
            <!-- Row -->
            <div class="items-center gap-8 lg:grid lg:grid-cols-2 xl:gap-16">
                <div class="text-gray-500 sm:text-lg dark:text-gray-400">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Gerencie sua
                        academia, crossfit ou personal training com facilidade</h2>
                    <p class="mb-8 font-light lg:text-xl">O MyFit System oferece uma plataforma completa para gerenciar
                        sua academia, crossfit ou personal training, com recursos para acompanhar o progresso dos
                        alunos, agendar aulas e gerenciar pagamentos.</p>
                    <!-- Lista de recursos -->
                    <ul role="list" class="pt-8 space-y-5 border-t border-gray-200 my-7 dark:border-gray-700">
                        <li class="flex space-x-3">
                            <!-- Ícone -->
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">Acompanhe o
                                progresso dos alunos</span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Ícone -->
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">Agende
                                aulas e gerencie pagamentos</span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Ícone -->
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">Gerencie
                                seu negócio com facilidade</span>
                        </li>
                    </ul>
                    <p class="mb-8 font-light lg:text-xl">O MyFit System é a solução perfeita para academias, crossfits
                        e personal trainers que desejam gerenciar seu negócio de forma eficiente.</p>
                </div>
                <img class="hidden w-full mb-4 rounded-lg lg:mb-0 lg:flex" src="{{ asset('img/feature-1.png') }}"
                     alt="Imagem de recurso do painel">
            </div>
            <!-- Row -->
            <div class="items-center gap-8 lg:grid lg:grid-cols-2 xl:gap-16">
                <img class="hidden w-full mb-4 rounded-lg lg:mb-0 lg:flex" src="{{ asset('img/feature-2.png') }}"
                     alt="Imagem do recurso 2">
                <div class="text-gray-500 sm:text-lg dark:text-gray-400">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Acompanhe seu
                        progresso e alcance seus objetivos</h2>
                    <p class="mb-8 font-light lg:text-xl">O MyFit System oferece uma plataforma completa para
                        acompanhar o progresso dos alunos, agendar aulas e gerenciar pagamentos.</p>
                    <!-- Lista de recursos -->
                    <ul role="list" class="pt-8 space-y-5 border-t border-gray-200 my-7 dark:border-gray-700">
                        <li class="flex space-x-3">
                            <!-- Ícone -->
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">Acompanhe
                                seu progresso físico</span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Ícone -->
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">Agende
                                aulas e gerencie pagamentos</span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Ícone -->
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">Alcance
                                seus objetivos com o MyFit System</span>
                        </li>
                    </ul>
                    <p class="font-light lg:text-xl">
                        O MyFit System é a solução perfeita para academias, crossfits e personal trainers que desejam
                        acompanhar o progresso dos alunos e alcançar seus objetivos.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End block -->
    <!-- Start block -->
    <section class="bg-white dark:bg-gray-900">
        <div
             class="items-center max-w-screen-xl px-4 py-8 mx-auto lg:grid lg:grid-cols-4 lg:gap-16 xl:gap-24 lg:py-24 lg:px-6">
            <div class="col-span-2 mb-8">
                <p class="text-lg font-medium text-red-600 dark:text-red-500">Revolutionando o Fitness com
                    Tecnologia</p>
                <h2 class="mt-3 mb-4 text-3xl font-extrabold tracking-tight text-gray-900 md:text-3xl dark:text-white">
                    O sistema de gerenciamento de fitness mais inovador</h2>
                <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">O MyFit System é uma plataforma
                    completa para academias, crossfits e personal trainers, oferecendo uma experiência de usuário única
                    e integrada.</p>
                <div class="pt-6 mt-6 space-y-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <a href="#"
                           class="inline-flex items-center text-base font-medium text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-700">
                            Conheça as Vantagens do MyFit System
                            <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="<path"
                                 target="_blank" rel="noreferrer">http://www.w3.org/2000/svg"><path
                                      fill-rule="evenodd"
                                      d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path></svg>
                        </a>
                    </div>
                    <div>
                        <a href="#"
                           class="inline-flex items-center text-base font-medium text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-700">
                            Cadastre-se Agora e Experimente
                            <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="<path"
                                 target="_blank" rel="noreferrer">http://www.w3.org/2000/svg"><path
                                      fill-rule="evenodd"
                                      d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-span-2 space-y-8 md:grid md:grid-cols-2 md:gap-12 md:space-y-0">
                <div>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Sistema de Gestão Integrado</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Gerencie sua academia, crossfit ou personal
                        training com facilidade e eficiência.</p>
                </div>
                <div>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Acompanhe seu Progresso</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Acompanhe seus exercícios, agende aulas e
                        acompanhe seu rendimento físico.</p>
                </div>
                <div>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Login Único e Seguro</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Acesse todos os recursos do MyFit System com
                        um único login seguro.</p>
                </div>
                <div>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Perfis de Usuário Personalizados</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Crie perfis de usuário personalizados para
                        admin, nutricionista, assistente e instrutor.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End block -->
    <!-- Start block -->
    <section class="bg-gray-50 dark:bg-gray-800">
        <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-24 lg:px-6">
            <figure class="max-w-screen-md mx-auto">
                <svg class="h-12 mx-auto mb-3 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 27"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z"
                          fill="currentColor" />
                </svg>
                <blockquote>
                    <p class="text-xl font-medium text-gray-900 md:text-2xl dark:text-white">
                        "O MyFit System é simplesmente incrível. Ele oferece uma gama completa de recursos para gestão
                        de academias e personal trainers, desde o acompanhamento de treinos até o gerenciamento de
                        pagamentos. É a escolha perfeita para elevar o seu negócio a um novo nível."</p>
                </blockquote>
                <figcaption class="flex items-center justify-center mt-6 space-x-3">
                    <img class="w-6 h-6 rounded-full"
                         src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/michael-gouch.png"
                         alt="foto de perfil">
                    <div class="flex items-center divide-x-2 divide-gray-500 dark:divide-gray-700">
                        <div class="pr-3 font-medium text-gray-900 dark:text-white">João Silva</div>
                        <div class="pl-3 text-sm font-light text-gray-500 dark:text-gray-400">CEO da Academia Fit</div>
                    </div>
                </figcaption>
            </figure>
        </div>
    </section>
    <!-- End block -->
    <!-- Start block -->
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 py-8 mx-auto lg:py-24 lg:px-6">
            <div class="max-w-screen-md mx-auto mb-8 text-center lg:mb-12">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">MyFit System: Controle Completo para Academias e Personal Trainers</h2>
                <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">Descubra como o MyFit System pode transformar a gestão do seu negócio e melhorar a experiência dos seus alunos.</p>
            </div>
            <div class="space-y-8 lg:grid lg:grid-cols-2 sm:gap-6 xl:gap-10 lg:space-y-0">
                <!-- Vantagens para Gestão -->
                <div class="flex flex-col max-w-lg p-6 mx-auto text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                    <h3 class="mb-4 text-2xl font-semibold">Para a Gestão</h3>
                    <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Soluções completas para gerenciar seu negócio com eficiência.</p>
                    <!-- Lista de Vantagens -->
                    <ul role="list" class="mb-8 space-y-4 text-left">
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Cadastro de informações do negócio</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Cadastro de exercícios e modalidades</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Múltiplos perfis de acesso</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Relatórios detalhados e análises de desempenho</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Sistema seguro com login único</span>
                        </li>
                    </ul>
                    <a href="#" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white  dark:focus:ring-red-900">Saiba mais</a>
                </div>
                <!-- Vantagens para Alunos -->
                <div class="flex flex-col max-w-lg p-6 mx-auto text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                    <h3 class="mb-4 text-2xl font-semibold">Para os Alunos</h3>
                    <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Acompanhe seu progresso e gerencie suas atividades com facilidade.</p>
                    <!-- Lista de Vantagens -->
                    <ul role="list" class="mb-8 space-y-4 text-left">
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Acompanhamento de exercícios físicos</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Agendamento de aulas</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Monitoramento de rendimento físico</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Gestão de pagamentos realizados</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                            <span>Acesso seguro com login único</span>
                        </li>
                    </ul>
                    <a href="#" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white  dark:focus:ring-red-900">Saiba mais</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End block -->
    <!-- Start block -->
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 py-8 mx-auto text-left lg:py-24 lg:px-6">
            <h2
                class="mb-6 text-3xl font-extrabold tracking-tight text-center text-gray-900 lg:mb-8 lg:text-3xl dark:text-white">
                Perguntas frequentes</h2>
            <div class="max-w-screen-md mx-auto">
                <div id="accordion-flush" data-accordion="collapse"
                     data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                     data-inactive-classes="text-gray-500 dark:text-gray-400">
                    <h3 id="accordion-flush-heading-1">
                        <button type="button"
                                class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-900 bg-white border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                data-accordion-target="#accordion-flush-body-1" aria-expanded="true"
                                aria-controls="accordion-flush-body-1">
                            <span>O que é o MyFit System?</span>
                            <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-1" class="" aria-labelledby="accordion-flush-heading-1">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">O MyFit System é uma plataforma de gestão
                                e apoio para academias, crossfits e personal trainers que desejam acompanhar o progresso
                                dos alunos e alcançar seus objetivos.
                            </p>
                        </div>
                    </div>
                    <h3 id="accordion-flush-heading-2">
                        <button type="button"
                                class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                                data-accordion-target="#accordion-flush-body-2" aria-expanded="false"
                                aria-controls="accordion-flush-body-2">
                            <span>Como funciona o MyFit System?</span>
                            <svg data-accordion-icon="" class="w-6 h-6 shrink-0" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">O MyFit System é uma tecnologia de gestão
                                que reúne as informações do seu negócio em um único lugar, permitindo que você acompanhe
                                o progresso dos alunos, gerencie contratos e vendas, e automatize tarefas.
                            </p>
                        </div>
                    </div>
                    <h3 id="accordion-flush-heading-3">
                        <button type="button"
                                class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                                data-accordion-target="#accordion-flush-body-3" aria-expanded="false"
                                aria-controls="accordion-flush-body-3">
                            <span>Quais são os benefícios do MyFit System?
                            </span>
                            <svg data-accordion-icon="" class="w-6 h-6 shrink-0" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">O MyFit System ajuda a aumentar a
                                eficiência do seu negócio, reduzir custos e melhorar a experiência do aluno. Além disso,
                                você pode acompanhar o progresso dos alunos, gerenciar contratos e vendas, e automatizar
                                tarefas.
                            </p>
                            <ul class="pl-5 text-gray-500 list-disc dark:text-gray-400">
                                <li><a href="#"
                                       class="text-red-600 dark:text-red-500 hover:underline">Sistema do
                                        Aluno</a>
                                </li>
                                <li><a href="#"
                                       class="text-red-600 dark:text-red-500 hover:underline">Sistema da
                                        Gestão</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h3 id="accordion-flush-heading-4">
                        <button type="button"
                                class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                                data-accordion-target="#accordion-flush-body-4" aria-expanded="false"
                                aria-controls="accordion-flush-body-4">
                            <span>Quem pode usar o MyFit System?
                            </span>
                            <svg data-accordion-icon="" class="w-6 h-6 shrink-0" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-4" class="hidden" aria-labelledby="accordion-flush-heading-4">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">O MyFit System é ideal para academias,
                                crossfits e personal trainers que desejam acompanhar o progresso dos alunos e alcançar
                                seus objetivos.</p>
                        </div>
                    </div>
                    <h3 id="accordion-flush-heading-5">
                        <button type="button"
                                class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                                data-accordion-target="#accordion-flush-body-5" aria-expanded="false"
                                aria-controls="accordion-flush-body-5">
                            <span>Como posso começar a usar o MyFit System?
                            </span>
                            <svg data-accordion-icon="" class="w-6 h-6 shrink-0" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-5" class="hidden" aria-labelledby="accordion-flush-heading-5">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">Para começar a usar o MyFit System, basta
                                se cadastrar em nosso site e seguir as instruções de configuração. Se tiver alguma
                                dúvida, nossa equipe de suporte estará disponível para ajudar.

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End block -->
    <!-- Start block -->
    <section class="bg-gray-50 dark:bg-gray-800">
        <div class="max-w-screen-xl px-4 py-8 mx-auto lg:py-16 lg:px-6">
            <div class="max-w-screen-sm mx-auto text-center">
                <h2 class="mb-4 text-3xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white">
                    Inicie seu teste grátis hoje</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">Experimente o MyFit System por
                    30 dias. Sem cartão de crédito necessário.</p>
                <a href="#"
                   class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Teste
                    grátis por 30 dias</a>
            </div>
        </div>
    </section>
    <!-- End block -->
</x-site-layout>
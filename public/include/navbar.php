<nav class="z-20 bg-blue-500 fixed top-0 w-screen">
    <div class="flex flex-row items-center justify-evenly">
        <div class="w-1/3">
            <button class="h-16 w-16 text-2xl text-gray-100 font-bold hover:text-white transition-all" type="button"
                    data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation"
                    aria-controls="drawer-navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <div class="w-1/3 text-center">
            <h1 class="text-2xl font-bold text-white transition-all">
                Forum
            </h1>
        </div>
        <div class="w-1/3"></div>
    </div>
</nav>

<div id="drawer-navigation"
     class="fixed top-0 left-0 z-50 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800"
     tabindex="-1" aria-labelledby="drawer-navigation-label">
    <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">
        navigation</h5>
    <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
             xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Fermer le menu</span>
    </button>
    <div class="py-4 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="../../public/views/home.php" data-drawer-hide="drawer-navigation" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-house text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                    <span class="ms-3">Accueil</span>
                </a>
            </li>
            <li>
                <a href="#" data-drawer-hide="drawer-navigation"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-user text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                    <span class="flex-1 ms-3">Moi</span>
                </a>
            </li>
            <li>
                <a href="#" data-drawer-hide="drawer-navigation"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-tarp text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                    <span class="flex-1 ms-3">Projets</span>
                </a>
            </li>
            <li>
                <a href="#" data-drawer-hide="drawer-navigation"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-envelope text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                    <span class="flex-1 ms-3">Contactez-moi</span>
                </a>
            </li>
            <li>
                <a href="#"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                         aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Sign In</span>
                </a>
            </li>
        </ul>
    </div>
</div>
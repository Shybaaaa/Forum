<?php

if (isset($_SESSION["user"])){
    $pdo = dbConnect();
    $sql = "SELECT name FROM roles where id = " . $_SESSION["user"]["roleId"];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $role = $stmt->fetch();
}
?>

<nav class="z-20 bg-blue-500 sticky top-0 w-screen">
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
    class="fixed top-0 left-0 z-50 select-none w-64 h-screen p-4 overflow-y-hidden transition-transform -translate-x-full bg-white dark:bg-gray-800"
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
    <div class="h-full w-full py-4 items-center flex flex-col justify-between">
        <div class="w-full">
            <ul class="font-medium">
                <li>
                    <a href="/index.php?page=home" data-drawer-hide="drawer-navigation"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
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

                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>
                <script>
                        
                    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                            document.documentElement.classList.add('dark');
                    } else {
                            document.documentElement.classList.remove('dark')
                        }
                    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
                    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
                
                    // Change the icons inside the button based on previous settings
                    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        themeToggleLightIcon.classList.remove('hidden');
                    } else {
                        themeToggleDarkIcon.classList.remove('hidden');
                    }
                
                    var themeToggleBtn = document.getElementById('theme-toggle');
                
                    themeToggleBtn.addEventListener('click', function() {
                    
                        // toggle icons inside button
                        themeToggleDarkIcon.classList.toggle('hidden');
                        themeToggleLightIcon.classList.toggle('hidden');
                    
                        // if set via local storage previously
                        if (localStorage.getItem('color-theme')) {
                            if (localStorage.getItem('color-theme') === 'light') {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('color-theme', 'dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('color-theme', 'light');
                            }
                        
                        // if NOT set via local storage previously
                        } else {
                            if (document.documentElement.classList.contains('dark')) {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('color-theme', 'light');
                            } else {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('color-theme', 'dark');
                            }
                        }

                        });
                </script>
                </li>
            </ul>
        </div>
        <div class="w-full mb-4 select-none">
            <?php if (isset($_SESSION["user"]) && $_SESSION["user"]): ?>
                <div type="button" data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start" class="flex items-center gap-3 rounded p-1 w-full hover:bg-gray-100">
                    <?php if ($_SESSION["user"]["image"]): ?>
                        <img class="w-10 h-10 rounded-full" src="<?= $_SESSION["user"]["image"] ?>" alt="">
                    <?php else: ?>
                        <i class="fa-solid fa-user text-xl rounded-full p-3 bg-gray-500 text-white"></i>
                    <?php endif; ?>
                    <div class="font-medium dark:text-white">
                        <div><?= $_SESSION["user"]["username"] ?></div>
                        <div class="text-sm text-gray-500 dark:text-gray-400"><?= $role["name"]  ?></div>
                    </div>
                </div>
                <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Tableau de bord</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Paramètres</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Support</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="/index.php?disconnect=1" class="text-red-600 block px-4 py-2 text-sm hover:text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            <i class="fa-solid fa-right-from-bracket font-light ml-1.5"></i>
                            Déconnexion
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/public/views/login.php" class="flex font-medium p-2 items-center text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-right-to-bracket text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                    <span class="flex-1 ms-3">Connexion</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
if (isset($_SESSION["user"])) {
    $pdo = dbConnect();
    $sql = "SELECT name From roles where id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION["user"]["roleId"]]);
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
                        <span class="flex-1 ms-3">a changer</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-drawer-hide="drawer-navigation"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-tarp text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                        <span class="flex-1 ms-3">a changer</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-drawer-hide="drawer-navigation"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-envelope text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                        <span class="flex-1 ms-3">a changer</span>
                    </a>
                </li>
                <li>
            </li>
        </ul>
    </div>
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
                        <div class="text-sm text-gray-500 dark:text-gray-400"><?= $role["name"] ?></div>
                    </div>
                </div>
                <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                        <li>
                            <a href="/public/views/dashboard/index.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Tableau de bord</a>
                        </li>
                        <li>
                            <a href="/public/views/parametres.php?page=setting" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Paramètres</a>
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
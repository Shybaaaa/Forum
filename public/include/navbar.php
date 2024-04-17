<?php
if (isset($_SESSION["user"])) {
    $pdo = dbConnect();
    $query = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
    $query->execute([$_SESSION["user"]["roleId"]]);
    $role = $query->fetch();
}
require_once'dark_mode.php'
?>

<nav class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
    <div class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="h-full w-full py-4 items-center flex flex-col justify-between">
            <div class="w-full">
                <ul class="font-medium">
                    <li>
                        <a href="/index.php?page=home" data-drawer-hide="drawer-navigation" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fa-solid fa-house text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                            <span class="ms-3">Accueil</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-drawer-hide="drawer-navigation" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fa-solid fa-user text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                            <span class="flex-1 ms-3">a changer</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-drawer-hide="drawer-navigation" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fa-solid fa-tarp text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                            <span class="flex-1 ms-3">a changer</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-drawer-hide="drawer-navigation" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fa-solid fa-envelope text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                            <span class="flex-1 ms-3">a changer</span>
                        </a>
                    </li>
                    <li>
                        <div>
                            <button>
                                <?php darkMode(); ?>
                            </button>
                        </div>
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
                            <div class="text-sm text-gray-500 dark:text-gray-400"><?= $role["name"] ?></div>
                        </div>
                    </div>
                    <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                            <li>
                                <a href="/public/views/dashboard/index.php?page=myaccount" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Tableau de bord</a>
                            </li>
                            <li>
                                <a href="/public/views/dashboard/setting.php?page=setting" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Paramètres</a>
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
</nav>
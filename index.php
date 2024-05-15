<?php
ob_start();
session_start();
require_once __DIR__ . "/private/functions/functions.php";
dbConnect();
reloadSession();

if (isset($_GET["disconnect"]) && $_GET["disconnect"] == 1) {
    disconnect();
}

if (isset($_SESSION["user"])) {
    $role = getRole($_SESSION["user"]["roleId"]);
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Blog de partage de connaissance">
    <meta name="keywords" content="Blog, partage, connaissance, informatique, développement, web, programmation">
    <meta name="author" content="G1">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum</title>
    <link rel="icon" href="public/image/logo.ico">
    <link rel="stylesheet" href="/public/css/main.css">
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.querySelector('html').classList.add('dark');
        } else {
            document.querySelector('html').classList.remove('dark');
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-slate-800">
    <!--<div id="loader" class="fixed top-0 left-0 z-[99] w-screen h-screen bg-white flex flex-col space-y-2 items-center justify-center">-->
    <!--    <i class="fa-solid fa-spinner motion-safe:animate-spin text-indigo-500 text-7xl"></i>-->
    <!--    <h2 class="text-xl font-medium text-gray-800">Chargement de la page.</h2>-->
    <!--</div>-->

    <?= renderNotification() ?>

    <div class="">
        <div class="hidden xl:fixed xl:inset-y-0 xl:flex xl:w-72 xl:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white shadow-lg px-6 rounded-r-2xl ring-1 ring-white/5 dark:bg-slate-700">
                <div class="flex h-16 shrink-0 items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Forum</h4>
                    <span>
                        <button id="theme-toggle" type="button" class="text-gray-500 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </span>
                </div>
                <nav class="flex flex-1 flex-col" class="">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li class="mx-auto w-full">
                                    <a href="index.php?page=home" class="text-gray-500 text-left hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 transition-all group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold dark:text-slate-200">
                                        <i class="fa-solid fa-house"></i>
                                        Accueil
                                    </a>
                                </li>
                            </ul>
                        </li>





                        <li class="mx-auto mt-auto mb-4 w-full">
                            <?php if (isset($_SESSION["user"]) && $_SESSION["user"]) : ?>
                                <div type="button" data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start" class="flex items-center gap-3 rounded px-6 mx-auto py-2 w-full mb-5 hover:bg-gray-50 dark:hover:bg-slate-500 transition-all">
                                    <?php if ($_SESSION["user"]["image"]) : ?>
                                        <img class="w-10 h-10 rounded-full" src="<?= $_SESSION["user"]["image"] ?>" alt="">
                                    <?php else : ?>
                                        <i class="fa-solid fa-user text-xl rounded-full p-3 bg-gray-500 text-white"></i>
                                    <?php endif; ?>
                                    <div class="font-medium">
                                        <div class="dark:text-slate-100"><?= $_SESSION["user"]["username"] ?></div>
                                        <div class="text-sm text-gray-500 dark:text-slate-100"><?= $role["name"] ?></div>
                                    </div>
                                </div>
                                <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md w-44 dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                                        <li>
                                            <a href="index.php?page=profil&ref=<?= $_SESSION["user"]["reference"] ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Mon profil</a>
                                        </li>
                                        <li>
                                            <a href="/public/views/dashboard/index.php?page=myaccount" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Tableau de bord</a>
                                        </li>
                                    </ul>
                                    <div class="py-1">
                                        <a href="/index.php?disconnect=1" class="text-red-600 block px-4 py-2 text-sm hover:text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                            <i class="fa-solid fa-right-from-bracket font-light ml-1.5"></i>
                                            Déconnexion
                                        </a>
                                    </div>
                                </div>
                            <?php else : ?>
                                <a href="/public/views/login.php" class="flex font-medium p-2 items-center text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-200 group">
                                    <i class="fa-solid fa-right-to-bracket text-gray-500 group-hover:text-gray-900 duration-75 transition"></i>
                                    <span class="flex-1 ms-3">Connexion</span>
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="xl:pl-72">
            <main>
                <?php
                if (isset($_GET["page"])) {
                    switch ($_GET["page"]) {
                        case "home":
                            require_once "public/views/home.php";
                            break;
                        case "viewpost":
                            require_once "public/views/viewpost.php";
                            break;
                        case "viewCategory":
                            require_once "public/views/viewCategory.php";
                            break;
                        case "profil":
                            require_once "public/views/profil.php";
                            break;
                        default:
                            require_once "public/views/home.php";
                            break;
                    }
                } else {
                    require_once "public/views/home.php";
                }
                ?>

                <?php require_once("public/include/footer.php") ?>
            </main>
        </div>
    </div>

    <script src="/public/js/notification.js"></script>
    <script src="/public/js/loader.js"></script>
    <script src="/public/js/dark_mode.js"></script>

</body>

</html>

<?php ob_end_flush(); ?>
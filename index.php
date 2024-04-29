<?php
session_start();
require_once "private/functions/functions.php";
dbConnect();
$config = parse_ini_file("config.ini");

if (isset($_GET["disconnect"]) && $_GET["disconnect"] == 1) {
    disconnect();
}

if (isset($_SESSION["user"])) {
    $role = getRole($_SESSION["user"]["roleId"]);
}

?>

<!doctype html>
<html lang="fr" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $config["APP_NAME"]?></title>
    <link rel="icon" href="public/image/logo.ico">
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body class="bg-gray-100">
<?php if (isset($_GET["success"]) and $_GET["success"] == 1): ?>
    <div id="toast-success"
         class="fixed top-20 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow"
         role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <i class="fa-solid fa-thumbs-up fill-current" aria-hidden="true"></i>
            <span class="sr-only">icon success</span>
        </div>
        <div class="ms-3 text-sm font-normal"><?= $_GET["message"] ?></div>
        <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8"
                data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Fermer</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m1 1 6 6m0 0 6 6M7 7l6-6M7
                    7l-6 6"/>
            </svg>
        </button>
    </div>
<?php endif; ?>

<div>
    <div class="hidden xl:fixed xl:inset-y-0 xl:z-50 xl:flex xl:w-72 xl:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white shadow-lg px-6 rounded-r-2xl ring-1 ring-white/5">
            <div class="flex h-16 shrink-0 items-center">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white"><?= $config["APP_NAME"] ?></h4>
            </div>
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li class="mx-auto w-full">
                                <a href="index.php?page=home" class="text-gray-500 text-left hover:text-gray-600 hover:bg-gray-100 transition-all group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                    <i class="fa-solid fa-house"></i>
                                    Accueil
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="mx-auto mt-auto mb-4 w-full">
                        <?php if (isset($_SESSION["user"]) && $_SESSION["user"]): ?>
                            <div type="button" data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start" class="flex items-center gap-3 rounded px-6 mx-auto py-2 w-full mb-5 hover:bg-gray-100 transition-all">
                                <?php if ($_SESSION["user"]["image"]): ?>
                                    <img class="w-10 h-10 rounded-full" src="<?= $_SESSION["user"]["image"] ?>" alt="">
                                <?php else: ?>
                                    <i class="fa-solid fa-user text-xl rounded-full p-3 bg-gray-500 text-white"></i>
                                <?php endif; ?>
                                <div class="font-medium">
                                    <div><?= $_SESSION["user"]["username"] ?></div>
                                    <div class="text-sm text-gray-500"><?= $role["name"] ?></div>
                                </div>
                            </div>
                            <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                                    <li>
                                        <a href="/public/views/dashboard/index.php?page=myaccount" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Tableau de bord</a>
                                    </li>
                                    <li>
                                        <a href="index.php?page=profil&ref=<?= $_SESSION["user"]["reference"] ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mon profil</a>
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
</body>
</html>
<?php
session_start();
require_once "../../../private/functions/functions.php";

if (!isset($_SESSION["user"])) {
    header("Location: /index.php");
}

$config = parse_ini_file("../../../config.ini");
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum - Tableau de bord</title>
    <link rel="stylesheet" href="../../css/main.css">
    <script src="https://kit.fontawesome.com/abcb30c057.js"></script>
</head>
<body>
<?php if (isset($_GET["error"])): ?>
    <div id="toast-danger"
        class="fixed top-20 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
        role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
            </svg>
            <span class="sr-only">icon erreur</span>
        </div>
        <div class="ms-3 text-sm font-normal"><?= $msgError ?></div>
        <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-danger" aria-label="Close">
            <span class="sr-only">Fermer</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
<?php endif; ?>
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
<aside class="ml-[-100%] fixed z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between h-screen bg-white transition duration-300 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]">
    <div>
        <div class="mx-6 px-6 py-4">
            <a href="/index.php" class="flex flex-row items-center justify-center">
                <span class="text-base font-bold ml-3 text-gray-600">Forum</span>
            </a>
        </div>

        <div class="flex flex-col items-center justify-center mt-5">
            <div class="h-32 w-32 rounded-full bg-gray-500 text-white flex justify-center items-center">
                <?php if ($_SESSION["user"]["image"] == ""): ?>
                    <i class="fa-solid fa-user text-6xl"></i>
                <?php else: ?>
                    <img src="<?= $_SESSION["user"]["image"] ?>" alt="Image de profil" class="h-32 w-32 rounded-full object-cover">
                <?php endif; ?>
            </div>
            <div class="flex flex-col text-center mt-2 mb-2">
                <span class="mt-4 text-xl font-semibold text-gray-600 lg:blockd"><?= $_SESSION["user"]["username"] ?></span>
                <span class="font-bold uppercase text-sm text-gray-400 tracking-tight subpixel-antialiased"><?= getRole($_SESSION["user"]["roleId"])["name"] ?></span>
            </div>
        </div>
        <ul class="space-y-2 tracking-wide mt-8 flex flex-col gap-y-1 justify-center text-gray-600 items-center">
            <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold <?php if(isset($_GET["page"]) and $_GET["page"] == "myaccount"){echo "font-semibold text-white bg-gradient-to-tl rounded-xl shadow-md from-cyan-500 to-indigo-500 scale-105";} else {echo "transition-all text-gray-500 group";}?>">
                <a href="index.php?page=myaccount">
                    <i class="fa-solid fa-user mr-2 text-lg group-hover:text-cyan-400"></i>
                    <span class="group-hover:text-gray-600">Mon compte</span>
                </a>
            </li>
            <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold <?php if(isset($_GET["page"]) and $_GET["page"] == "ttt"){echo "font-semibold text-white bg-gradient-to-tl rounded-xl shadow-md from-cyan-500 to-indigo-500 scale-105";} else {echo "transition-all text-gray-500 group";}?>">
                <a href="index.php?page=null">
                    <i class="fa-solid fa-user mr-2 text-lg group-hover:text-cyan-400"></i>
                    <span class="group-hover:text-gray-600">Mon compte</span>
                </a>
            </li>
            <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold <?php if(isset($_GET["page"]) and $_GET["page"] == "ttt"){echo "font-semibold text-white bg-gradient-to-tl rounded-xl shadow-md from-cyan-500 to-indigo-500 scale-105";} else {echo "transition-all text-gray-500 group";}?>">
                <a href="index.php?page=null">
                    <i class="fa-solid fa-user mr-2 text-lg group-hover:text-cyan-400"></i>
                    <span class="group-hover:text-gray-600">Mon compte</span>
                </a>
            </li>
            <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold <?php if(isset($_GET["page"]) and $_GET["page"] == "ttt"){echo "font-semibold text-white bg-gradient-to-tl rounded-xl shadow-md from-cyan-500 to-indigo-500 scale-105";} else {echo "transition-all text-gray-500 group";}?>">
                <a href="index.php?page=null">
                    <i class="fa-solid fa-user mr-2 text-lg group-hover:text-cyan-400"></i>
                    <span class="group-hover:text-gray-600">Mon compte</span>
                </a>
            </li>
            <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold <?php if(isset($_GET["page"]) and $_GET["page"] == "ttt"){echo "font-semibold text-white bg-gradient-to-tl rounded-xl shadow-md from-cyan-500 to-indigo-500 scale-105";} else {echo "transition-all text-gray-500 group";}?>">
                <a href="index.php?page=null">
                    <i class="fa-solid fa-user mr-2 text-lg group-hover:text-cyan-400"></i>
                    <span class="group-hover:text-gray-600">Mon compte</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="px-6 -mx-6 pt-4 flex justify-between items-center">
        <a class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group" href="/index.php?disconnect=1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-red-600 transition-all" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="group-hover:text-red-600 transition-all cursor-pointer ">Déconnexion</span>
        </a>
    </div>
</aside>
<div class="ml-auto mb-6 overflow-x-hidden lg:w-[75%] xl:w-[80%] 2xl:w-[85%]">
    <div class="flex items-center sticky z-40 top-0 h-16 bg-white lg:py-2.5">
        <div class="px-6 flex items-center justify-between ">
            <h5 hidden class="text-2xl text-gray-600 font-medium lg:block">
                Tableau de bord
            </h5>
        </div>
    </div>
    <div id="app" class="px-6 pt-6 h-screen flex-col flex items-center w-full rounded-tl-lg 2xl:container bg-slate-50">
        <?php
            if (isset($_GET["page"])){
                switch ($_GET["page"]):
                    case "myaccount":
                        require_once "myaccount.php";
                        break;
                    default:
                        require_once "myaccount.php";
                        break;
                endswitch;
            } else {
                require_once "myaccount.php";
            }
        ?>
    </div>
</div>

<script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>

<?php
ob_start();
session_start();
require_once __DIR__ . "/../../../private/functions/functions.php";

reloadSession();

if (!isset($_SESSION["user"])) {
    header("Location: /index.php");
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum - Tableau de bord</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="icon" href="/public/image/logo.ico">
    <script src="https://kit.fontawesome.com/abcb30c057.js" crossorigin="anonymous"></script>
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.querySelector('html').classList.add('dark');
        } else {
            document.querySelector('html').classList.remove('dark');
        }
    </script>
</head>
<body class="overflow-y-hidden">
<?= renderNotification() ?>
    <aside class="ml-[-100%] fixed z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between shadow-lg h-screen bg-white transition duration-300 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%] dark:bg-slate-700">
        <div>
            <div class="mx-6 px-6 py-4">
                <a href="/index.php" class="flex flex-row items-center justify-center">
                    <span class="text-base font-bold ml-3 text-gray-600 inline-flex items-center dark:text-slate-300"><i class="fa-solid fa-house mr-2"></i> Accueil</span>
                </a>
            </div>

            <div class="flex flex-col items-center justify-center mt-5 dark:bg-slate-700">
                <div class="h-32 w-32 rounded-full bg-gray-500 text-white shadow flex justify-center items-center">
                    <?php if ($_SESSION["user"]["image"] == "") : ?>
                        <i class="fa-solid fa-user text-6xl"></i>
                    <?php else : ?>
                        <img src="<?= $_SESSION["user"]["image"] ?>" alt="Image de profil" class="h-32 w-32 rounded-full object-cover">
                    <?php endif; ?>
                </div>
                <div class="flex flex-col text-center mt-2 mb-2">
                    <span class="mt-4 text-xl font-semibold text-gray-600 lg:blockd dark:text-slate-200"><?= $_SESSION["user"]["username"] ?></span>
                    <span class="font-bold uppercase text-sm text-gray-400 tracking-tight subpixel-antialiased dark:text-slate-400"><?= getRole($_SESSION["user"]["roleId"])["name"] ?></span>
                </div>
            </div>
            <ul class="space-y-2 tracking-wide mt-8 flex flex-col gap-y-1 justify-center text-gray-600 items-center">
                <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold <?php if (isset($_GET["page"]) and $_GET["page"] == "myaccount") {
                                                                                echo "font-semibold text-white bg-gradient-to-tl rounded-xl shadow-md from-cyan-500 to-indigo-500 scale-105";
                                                                            } else {
                                                                                echo "transition-all text-gray-500 group";
                                                                            } ?>">
                    <a href="index.php?page=myaccount">
                        <i class="fa-solid fa-user mr-2 text-lg group-hover:text-cyan-400"></i>
                        <span class="group-hover:text-gray-600 dark:text-slate-300">Mon compte</span>
                    </a>
                </li>
                <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold <?php if (isset($_GET["page"]) and $_GET["page"] == "mypost" or $_GET["page"] == "editPost" or $_GET["page"] == "addPost") {
                                                                                echo "font-semibold text-white bg-gradient-to-tl rounded-xl shadow-md from-cyan-500 to-indigo-500 scale-105";
                                                                            } else {
                                                                                echo "transition-all text-gray-500 group";
                                                                            } ?>">
                    <a href="index.php?page=mypost">
                        <i class="fa-solid fa-folder mr-2 text-lg group-hover:text-cyan-400"></i>
                        <span class="group-hover:text-gray-600 dark:text-slate-300">Mes posts</span>
                    </a>
                </li>
                <?php if ($_SESSION["user"]["roleId"] > 1) : ?>
                    <li class="py-2.5 px-3.5 w-full  duration-75 font-semibold group">
                        <button type="button" aria-controls="dropdown" data-collapse-toggle="dropdown">
                            <i class="fa-solid fa-user-tie mr-2 text-lg group-hover:text-cyan-400"></i>
                            <span class="group-hover:text-gray-600 dark:text-slate-300">Administration</span>
                        </button>
                        <ul id="dropdown" class="hidden py-2 space-y-2">
                            <li>
                                <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Accueil</a>
                            </li>
                            <li>
                                <a href="index.php?page=adminUser" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Utilisateurs</a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Tickets</a>
                            </li>
                            <li>
                                <a href="index.php?page=adminCategory" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Catégories</a>
                            </li>
                            <li>
                                <a href="index.php?page=adminPost" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Posts</a>
                            </li>
                            <li>
                                <a href="index.php?page=adminRole" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Roles</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="px-6 -mx-6 pt-4 flex justify-between items-center">
            <a class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group dark:text-slate-300" href="/index.php?disconnect=1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-red-600 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="group-hover:text-red-600 transition-all cursor-pointer ">Déconnexion</span>
            </a>
        </div>
    </aside>
    <div class="ml-auto mb-6 block max-h-screen overflow-y-hidden overflow-x-hidden lg:w-[75%] xl:w-[80%] 2xl:w-[85%] dark:bg-slate-800">
        <div class="flex shadow-sm items-center sticky top-0 z-10 h-16 bg-white lg:py-2.5 dark:bg-slate-800">
            <div class="px-6 flex items-center justify-between ">
                <h5 hidden class="text-2xl text-gray-600 block font-medium dark:text-slate-200 dark:bg-slate-8">
                    Tableau de bord
                </h5>
            </div>
        </div>
        <div id="app" class="px-6 pt-6 h-screen flex-col flex items-center w-full rounded-tl-lg 2xl:container bg-slate-50  dark:bg-slate-800">
            <?php
            if (isset($_GET["page"])) {
                switch ($_GET["page"]):
                    case "myaccount":
                        require_once "user/myaccount.php";
                        break;
                    case "mypost":
                        require_once "user/mypost.php";
                        break;
                    case "adminCategory":
                        require_once "admin/category.php";
                        break;
                    case "adminUser":
                        require_once "admin/user.php";
                        break;
                    case "adminPost":
                        require_once "admin/post.php";
                        break;
                    case "adminRole":
                        require_once "admin/role.php";
                        break;
                    case "editPost":
                        require_once "user/editPost.php";
                        break;
                    case "addPost":
                        require_once "user/addPost.php";
                        break;
                    default:
                        require_once "user/myaccount.php";
                        break;
                endswitch;
            } else {
                require_once "user/myaccount.php";
            }
            ?>
        </div>
    </div>

    <script type="module" src="/node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="/public/js/dark_mode.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
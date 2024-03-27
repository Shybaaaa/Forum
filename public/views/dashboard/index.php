<?php
session_start();
require_once "../../../private/functions/functions.php";

if (!isset($_SESSION["user"])) {
    header("Location: /index.php");
}

$config = parse_ini_file("../../../config.ini");

if (isset($_GET["disconnect"]) && $_GET["disconnect"] == 1) {
    disconnect();
}

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
    <script src="../../js/app.js"></script>
</head>
<body>
<aside class="ml-[-100%] fixed z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between h-screen bg-white transition duration-300 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]">
    <div>
        <div class="mx-6 px-6 py-4">
            <a href="/index.php" class="flex flex-row items-center justify-center">
                <span class="text-base font-bold ml-3 text-gray-600">Forum</span>
            </a>
        </div>

        <div class="flex flex-col items-center justify-center mt-5">
            <div class="h-32 w-32 rounded-full bg-gray-500 text-white flex justify-center items-center">
                <i class="fa-solid fa-user text-white text-xl"></i>
            </div>
            <div class="flex flex-col text-center mt-2 mb-2">
                <span class="mt-4 text-xl font-semibold text-gray-600 lg:blockd"><?= $_SESSION["user"]["username"] ?></span>
                <span class="font-bold uppercase text-sm text-gray-400 tracking-tight subpixel-antialiased"><?= getRole($_SESSION["user"]["roleId"])["name"] ?></span>
            </div>
        </div>
        <ul class="space-y-2 tracking-wide mt-8 flex flex-col items-center">
            <li>
                <a href=""></a>
            </li>
            <li>
                TEST
            </li>
            <li>
                TEST
            </li>
            <li>
                TEST
            </li>
            <li>
                TEST
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
<div class="ml-auto mb-6 lg:w-[75%] xl:w-[80%] 2xl:w-[85%]">
    <div class="sticky z-40 top-0 h-16 bg-white lg:py-2.5">
        <div class="px-6 flex items-center justify-between space-x-4 2xl:container">
            <h5 hidden class="text-2xl text-gray-600 font-medium lg:block">
                Tableau de bord
            </h5>
        </div>
    </div>
    <div class="px-6 pt-6 h-screen rounded-tl-lg 2xl:container bg-gray-50">
        div.app
    </div>
</div>

</body>
</html>
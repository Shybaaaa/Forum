<?php

require_once "../../private/functions/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    addPost($_POST["title"], $_POST["description"], $_POST["postCategoryId"]);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/main.css">
    <title>Document</title>
</head>
<body>
<div class="flex flex-col items-center justify-center min-h-screen bg-[url('/public/image/background/login_bg.jpeg')] bg-fixed bg-no-repeat bg-center bg-blend-darken">
    <div class="w-96 backdrop-blur-2xl p-6 rounded-xl bg-black/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">Post</h1>
        <form action="" method="post" class="mt-8 space-y-6">
            <!-- <div class="col-span-full">
                <label for="photo" class="block text-sm font-medium text-white">Photo</label>
                <div class="mt-2 flex items-center gap-x-3">
                    <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div> -->
        
            <div class="space-y-2">
                <label for="username" class="block text-sm font-medium text-white">Titre</label>
                <input type="text" name="username" id="username" autocomplete="username" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                </div>
            <div class="col-span-full">
                <label for="about" class="block text-sm font-medium text-white">Description</label>
                <textarea id="about" name="about" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50"></textarea>
                </div>
            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Catégories <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>

<!-- Dropdown menu -->
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                    <li>
                        <a href="" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Catégorie 1 </a>
                    </li>
                </ul>
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">Poster</button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
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
<!-- Button dropdown  -->       
            <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>Choisis une catégorie</option>
                <option value="US">sport</option>
                <option value="CA">jeux vidéo</option>
                <option value="FR">politique</option>
                <option value="DE">littérature</option>
            </select>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">Poster</button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
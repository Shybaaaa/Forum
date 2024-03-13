<?php 

    require('../../private/functions/functions.php');

    if ($_SERVER['REQUEST_METHOD'] ==='POST'){
        $username = $_POST['username'];
        $description = $_POST['description'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $vpassword = $_POST['vpassword'];

        adduser($_POST['username'], $_POST['description'], $_POST['email'], $_POST['password'], $_POST['vpassword']);
    }
?>


    ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum - Login</title>
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body class="overflow-hidden">

<div class="flex flex-col items-center justify-center min-h-screen bg-[url('/public/image/background/login_bg.jpeg')] bg-fixed bg-no-repeat bg-center bg-blend-darken">
    <div class="w-96 backdrop-blur-2xl p-6 rounded-xl bg-black/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">Inscription</h1>
        <form action="" method="post" class="mt-8 space-y-6">
            <div class="col-span-full">
                <label for="photo" class="block text-sm font-medium text-white">Photo</label>
                <div class="mt-2 flex items-center gap-x-3">
                    <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                    </svg>
                    <button type="button" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Change</button>
                </div>
            </div>
        
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-white">Adresse e-mail</label>
                <input type="email" id="email" name="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="space-y-2">
                <label for="username" class="block text-sm font-medium text-white">Username</label>
                <input type="text" name="username" id="username" autocomplete="username" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                </div>
            <div class="col-span-full">
                <label for="about" class="block text-sm font-medium text-white">Description</label>
                <textarea id="about" name="about" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50"></textarea>
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-white">Mot de passe</label>
                <input type="password" id="password" name="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-white">Entrez le même mot de passe</label>
                <input type="password" id="password" name="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">S'inscire</button>
            </div>
            
        </form>
        <div class="mt-2 text-center text-sm font-normal">
            <p class="text-white">Vous avez déja un compte ?<a href="../public/views/login.css" class="text-indigo-600 hover:text-indigo-500"> Connectez-vous</a></p>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/abcb30c057.js"></script>
<script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>
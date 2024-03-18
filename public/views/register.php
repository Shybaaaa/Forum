<?php 

    require('../../private/functions/functions.php');

    if ($_SERVER['REQUEST_METHOD'] ==='POST'){
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $vPassword = htmlspecialchars($_POST['vpassword']);

        if (isset($_POST["about"])){
            $description = htmlspecialchars($_POST["about"]);
        } else {
            $description = "";
        }

        adduser($username, $description, $email, $password, $vPassword, $_FILES['image']);
    }
?>


    
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum - register</title>
    <link rel="stylesheet" href="/public/css/main.css">
</head>
    <body class="overflow-hidden">
        <div class="flex flex-col items-center justify-center min-h-screen bg-[url('/public/image/background/login_bg.jpeg')] bg-fixed bg-no-repeat bg-center bg-blend-darken">
            <div class="w-96 backdrop-blur-2xl p-6 rounded-xl bg-black/10 shadow-2xl">
                <h1 class="text-4xl font-bold mb-3 text-center text-white">Création de compte</h1>
                <form action="" enctype="multipart/form-data" method="post" class="mt-8 space-y-6">
                    <div class="col-span-full">
                        <label for="photo" class="block text-sm font-medium text-white">Photo</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" name="image" aria-describedby="user_avatar_help" id="image" type="file">
                        </div>
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
                        <label for="email" class="block text-sm font-medium text-white">Adresse e-mail</label>
                        <input type="email" id="email" name="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                    </div>
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-white">Mot de passe</label>
                        <input type="password" id="password" name="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                    </div>
                    <div class="space-y-2">
                        <label for="vpassword" class="block text-sm font-medium text-white">Entrez le même mot de passe</label>
                        <input type="password" id="vpassword" name="vpassword" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                    </div>
                    <div>
                        <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">S'inscire</button>
                    </div>

                </form>
                <div class="mt-2 text-center text-sm font-normal">
                    <p class="text-white">Vous avez déja un compte ?<a href="./login.php" class="text-indigo-600 hover:text-indigo-500"> Connectez-vous</a></p>
                </div>
            </div>
        </div>


    <script src="https://kit.fontawesome.com/abcb30c057.js"></script>
    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
    </body>
</html>
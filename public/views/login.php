<?php
session_start();
require_once "../../private/functions/functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
//    $remember = $_POST["remember"];

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=1");
    } else {
        loginUser($email, $password);
    }
}
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
<?php if (isset($_GET["error"])): ?>
    <?php
    $msgError = "";

    switch ($_GET["error"]) {
        case 1:
            $msgError = "Veuillez remplir tous les champs.";
            break;
        case 2:
            $msgError = "Adresse e-mail ou mot de passe incorrect.";
            break;
        case 3:
            $msgError = "Veuillez entrer une adresse e-mail valide.";
            break;
    } ?>
    <div id="toast-danger"
        class="fixed top-5 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
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
        class="fixed top-5 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow"
        role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3a1 1 0 102 0v-3zm0 6a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
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
<<<<<<< HEAD
<!--<button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">-->
<!--Toggle modal-->
<!--</button>-->

=======
<!-- 
<button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
Toggle modal
</button> -->
<?php if ($users["isActive"] == 0); { ?>
>>>>>>> d313ed297150da21ab0a36c0eb8e27eea96c750c
<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
<<<<<<< HEAD
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Votre compte a été désactivé,voulez vous le réactiver?</h3>
                <?php
                    if ($users["isActive"] == 1) {
                ?>
=======
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Votre compte a été désactivé,voulez vous le réactiver</h3>
>>>>>>> d313ed297150da21ab0a36c0eb8e27eea96c750c
                <button data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Oui
                </button>
                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Non me connecter a un autre compte
                </button>
            </div>
        </div>
    </div>
</div>
<?php };?>

<div class="fixed top-5 left-10 cursor-pointer flex items-center w-fit max-w-sm text-white font-bold bg-black/10 rounded-xl text-medium backdrop-blur-2xl hover:text-gray-50 hover:bg-black/20 transition-all">
    <a href="/index.php" class="p-4">
        <i class="fa-solid fa-house"></i>
        Accueil
    </a>
</div>

<div class="flex flex-col items-center justify-center min-h-screen bg-[url('/public/image/background/login_bg.jpeg')] bg-fixed bg-no-repeat bg-center bg-blend-darken">
    <div class="w-96 backdrop-blur-2xl p-6 rounded-xl bg-black/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">Connexion</h1>
        <form action="" method="post" class="mt-8 space-y-6">
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-white">Adresse e-mail</label>
                <input type="email" id="email" name="email"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-white">Mot de passe</label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="rounded border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="remember" class="ml-2 block text-sm font-medium text-white">Se souvenir de moi</label>
                </div>
                <div>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">Mot de
                        passe oublié ?</a>
                </div>
            </div>
            <div>
                <button type="submit"
                        class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">
                    Connexion
                </button>
            </div>
        </form>
        <div class="mt-2 text-center text-sm font-normal">
            <p class="text-white">Vous n'avez pas de compte ? <a href="./register.php" class="text-indigo-600 hover:text-indigo-500">Inscrivez-vous</a>
            </p>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/abcb30c057.js"></script>
<script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>
<?php
session_start();
require_once "../../private/functions/functions.php";

if (isset($_SESSION["user"])) {
    newNotification("warning", "Vous êtes déjà connecté.", true, "fa-exclamation-circle");
    header("Location: /index.php");
}

if (isset($_COOKIE["status"])) {
    $desactivate = json_decode($_COOKIE["status"], true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["btnLogin"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        loginUser($email, $password);
    }

    if (isset($_POST["btnRestore"]) && $desactivate["isActive"] == 0) {
        loginRestore($desactivate["id"]);
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
    <link rel="icon" href="/public/image/logo.ico">
</head>
<body class="overflow-hidden">

<?= renderNotification() ?>

<div class="fixed top-5 left-10 cursor-pointer flex items-center w-fit max-w-sm text-white font-bold bg-black/10 rounded-xl text-medium backdrop-blur-2xl hover:text-gray-50 hover:bg-black/20 transition-all">
    <a href="/index.php" class="p-4">
        <i class="fa-solid fa-house"></i>
        Accueil
    </a>
</div>

<div class="flex flex-col items-center justify-center min-h-screen bg-[url('/public/image/background/login_bg.jpeg')] bg-fixed bg-no-repeat bg-center bg-blend-darken">
    <div class="w-96 backdrop-blur-2xl p-6 rounded-xl bg-black/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">Connexion</h1>
        <form action="" name="formLogin" method="post" class="mt-8 space-y-6">
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-white">Adresse e-mail</label>
                <input type="email" aria-required="true" id="email" name="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-white">Mot de passe</label>
                <input type="password" aria-required="true" id="password" name="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="flex items-center justify-end">
                <div>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">Mot de passe oublié ?</a>
                </div>
            </div>
            <div>
                <input value="Connexion" name="btnLogin" type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">
            </div>
        </form>
        <div class="mt-2 text-center text-sm font-normal">
            <p class="text-white">Vous n'avez pas de compte ? <a href="./register.php" class="text-indigo-600 hover:text-indigo-500">Inscrivez-vous</a></p>
        </div>
    </div>
</div>

<?php if (isset($desactivate) && $desactivate["isActives"] == 0): ?>
<div id="restoreModal" tabindex="-1" class="flex overflow-y-auto fixed top-0 right-0 left-0 backdrop-brightness-50 z-50 w-full h md:inset-0  max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" data-modal-target="restoreModal"  class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="restoreModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Votre compte a été désactivé, voulez-vous le réactiver</h3>
                <form method="post" action="" class="flex flex-row items-center justify-center gap-x-1">
                    <input value="Oui" name="btnRestore" id="btnRestore" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    <button data-modal-hide="restoreModal" data-modal-target="restoreModal" type="button" class="py-2.5 px-5 ms-3 text-xs font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Me connecter ou créer un autre compte
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<script src="/public/js/notification.js"></script>
<script src="https://kit.fontawesome.com/abcb30c057.js"></script>
<script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>
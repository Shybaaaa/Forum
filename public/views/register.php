<?php

require('../../private/functions/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $vPassword = htmlspecialchars($_POST['vpassword']);

    if (isset($_POST["about"])) {
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
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum - register</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="icon" href="/public/image/logo.ico">
</head>
<body class="overflow-hidden">

<?php if (isset($_GET["error"])): ?>
    <?php $msgError = $_GET["message"] ?>
    <div id="toast-danger"
        class="fixed top-5 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow"
        role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
            </svg>
            <span class="sr-only">icon erreur</span>
        </div>
        <div class="ms-3 text-sm font-normal"><?= $msgError ?></div>
        <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 "
                data-dismiss-target="#toast-danger" aria-label="Close">
            <span class="sr-only">Fermer</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
<?php endif; ?>

<div class="fixed top-5 left-10 cursor-pointer flex items-center w-fit max-w-sm text-white font-bold bg-black/10 rounded-xl text-medium backdrop-blur-2xl hover:text-gray-50 hover:bg-black/20 transition-all">
    <a href="/index.php" class="p-4">
        <i class="fa-solid fa-house"></i>
        Accueil
    </a>
</div>

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
                <input type="text" name="username" aria-required="true" id="username" autocomplete="username"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="col-span-full">
                <label for="about" class="block text-sm font-medium text-white">Description</label>
                <textarea id="about" name="about" rows="3"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50"></textarea>
            </div>
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-white">Adresse e-mail</label>
                <input type="email" id="email" aria-required="true" name="email"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-white">Mot de passe</label>
                <input type="password" aria-required="true" id="password" name="password"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="space-y-2">
                <label for="vpassword" class="block text-sm font-medium text-white">Entrez le même mot de passe</label>
                <input type="password" aria-required="true" id="vpassword" name="vpassword"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div>
                <button type="submit"
                        class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">
                    S'inscire
                </button>
            </div>

        </form>
        <div class="mt-2 text-center text-sm font-normal">
            <p class="text-white">Vous avez déja un compte ?<a href="./login.php"
                                                            class="text-indigo-600 hover:text-indigo-500">
                    Connectez-vous</a></p>
        </div>
    </div>
</div>


<?php require_once "../../../include/footer.php" ?>
</body>
</html>
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
    <div class="w-96 backdrop-blur-3xl p-5 rounded-xl bg-gray-900/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">Connexion</h1>
        <form action="" method="post" class="mt-8 space-y-6">
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-white">Adresse e-mail</label>
                <input type="email" id="email" name="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-white">Mot de passe</label>
                <input type="password" id="password" name="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
            </div>
            <div class="flex items
            -center justify-between">
                <div class="flex items
                -center">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="remember" class="ml-2 block text-sm font-medium text-white">Se souvenir de moi</label>
                </div>
                <div>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Mot de passe oublié ?</a>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">Connexion</button>
            </div>
        </form>
        <div class="mt-3 text-center text-sm">
            <p class="text-white">Vous n'avez pas de compte ? <a href="" class="text-indigo-600 hover:text-indigo-500">Inscrivez-vous</a></p>
        </div>
    </div>
</div>
</body>
</html>
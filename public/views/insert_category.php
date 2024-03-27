<?php

require_once "../../private/functions/functions.php";

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
    <div class="w-96 p-6 rounded-xl bg-black/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">ajouter une catégorie</h1>
        <form enctype="multipart/form-data" action="" method="post" class="mt-8 space-y-6">
            <div class="col-span-full">
            <div class="space-y-2">
            <form class="w-25 " action="./update.php" method="POST">
                <label for="artist" class="form-label">Nom de la catégoire</label>
                <input type="hidden" name="id" value="<?= $category["postCategory"] ?>">
                <input type="text" class="form-control" id="category" name="name" value="<?= $category["Name"] ?>">
        </div>

        <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">Envoyer</button>
        </form>
    </form>
    </div>
</div>

</body>
</html>
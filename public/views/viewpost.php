<?php

require_once "../../private/functions/functions.php";

$config = parse_ini_file("../../config.ini");

// $post = getPosts($_GET["post"]["id"]);

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
    <?php require_once "../../public/include/navbar.php" ?>
    <section class="w-full min-h-screen grid grid-cols-3 gap-x-7 gap-y-9 overflow-x-hidden">
    <?php getPosts($post)  ?>
                <article class="flex flex-col shadow w-auto h-auto">
            <!-- Article Image -->
            <a href="#?id=<?= $post["id"] ?>" class="hover:opacity-75">
                <img class="w-full h-2/3" src="<?= $post["photo"] ?>">
            </a>
            <div class="bg-white flex flex-col justify-start p-6">
                <a href="#" class="text-blue-700 text-sm font-bold uppercase pb-4"><?= getCategory($post["postCategoryId"])["name"] ?></a>
                <a href="#?id=<?= $post["id"] ?>" class="text-3xl font-bold hover:text-gray-700 pb-4"><?= $post["title"] ?></a>
                <p href="#?id=<?= $post["id"] ?>" class="text-sm pb-3">
                    Créé par <a href="#" class="font-semibold hover:text-gray-800"><?= getUser($post["createdBy"])["username"]  ?></a>, Publié le <?= $post["createdAt"] ?>
                </p>
                <a href="#?id=<?= $post["id"] ?>" class="pb-6"><?= $post["description"] ?></a>
                <!-- <a href="#" class="uppercase text-gray-800 hover:text-black">Afficher plus <i class="fas fa-arrow-right"></i></a> -->
            </div>
        </article>
    </section>

    <?php require_once "../../public/include/footer.php" ?>
</body>
</html>
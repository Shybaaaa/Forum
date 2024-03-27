<?php


?>
<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<div class="text-right mt-5">

<a type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" href="../public/views/insert.php">Ajouter un post</a>
    </div>
<a type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" href="../public/views/insert_post.php">Ajouter un post</a>
</div>
<div class="container mx-auto flex flex-wrap py-6">

<!-- Posts Section -->
<section class="w-full md:w-2/3 flex flex-col items-center px-3">

    <?php foreach (getPosts("all") as $post): ?>
        <article class="flex flex-col shadow my-4">
            <!-- Article Image -->
            <a href="#" class="hover:opacity-75">
                <img src="<?= $post["photo"] ?>">
            </a>
            <div class="bg-white flex flex-col justify-start p-6">
                <a href="#" class="text-blue-700 text-sm font-bold uppercase pb-4">Technologie</a>
                <a href="#" class="text-3xl font-bold hover:text-gray-700 pb-4"><?= $post["title"] ?></a>
                <p href="#" class="text-sm pb-3">
                    By <a href="#" class="font-semibold hover:text-gray-800">David Grzyb</a>, Published on April 25th, 2020
                </p>
                <a href="#" class="pb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus quis porta dui. Ut eu iaculis massa. Sed ornare ligula lacus, quis iaculis dui porta volutpat. In sit amet posuere magna..</a>
                <!-- <a href="#" class="uppercase text-gray-800 hover:text-black">Afficher plus <i class="fas fa-arrow-right"></i></a> -->
            </div>
        </article>
    <?php endforeach; ?>


    <article class="flex flex-col shadow my-4">
        <!-- Article Image -->
        <a href="#" class="hover:opacity-75">
            <img src="https://source.unsplash.com/collection/1346951/1000x500?sig=2">
        </a>
        <div class="bg-white flex flex-col justify-start p-6">
            <a href="#" class="text-blue-700 text-sm font-bold uppercase pb-4">Automotive, Finance</a>
            <a href="#" class="text-3xl font-bold hover:text-gray-700 pb-4">Lorem Ipsum Dolor Sit Amet Dolor Sit Amet</a>
            <p href="#" class="text-sm pb-3">
                By <a href="#" class="font-semibold hover:text-gray-800">David Grzyb</a>, Published on January 12th, 2020
            </p>
            <a href="#" class="pb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus quis porta dui. Ut eu iaculis massa. Sed ornare ligula lacus, quis iaculis dui porta volutpat. In sit amet posuere magna..</a>
            <!-- <a href="#" class="uppercase text-gray-800 hover:text-black">Afficher plus <i class="fas fa-arrow-right"></i></a> -->
        </div>
    </article>

    <article class="flex flex-col shadow my-4">
        <!-- Article Image -->
        <a href="#" class="hover:opacity-75">
            <img src="https://source.unsplash.com/collection/1346951/1000x500?sig=3">
        </a>
        <div class="bg-white flex flex-col justify-start p-6">
            <a href="#" class="text-blue-700 text-sm font-bold uppercase pb-4">Sports</a>
            <a href="#" class="text-3xl font-bold hover:text-gray-700 pb-4">Lorem Ipsum Dolor Sit Amet Dolor Sit Amet</a>
            <p href="#" class="text-sm pb-3">
                By <a href="#" class="font-semibold hover:text-gray-800">David Grzyb</a>, Published on October 22nd, 2019
            </p>
            <a href="#" class="pb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus quis porta dui. Ut eu iaculis massa. Sed ornare ligula lacus, quis iaculis dui porta volutpat. In sit amet posuere magna..</a>
            <!-- <a href="#" class="uppercase text-gray-800 hover:text-black">Afficher plus <i class="fas fa-arrow-right"></i></a> -->
        </div>
    </article>
</section>
</body>
</html>
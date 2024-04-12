<!DOCTYPE html>
<html lang="fr" class ="">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <div class="text-right mt-5">
        <a type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" href="../public/views/insert_post.php">Ajouter un post</a>
    </div>
    <div class="container mx-auto flex flex-wrap py-6">
        
        <!-- Posts Section -->
        <section class="w-full min-h-screen grid grid-cols-3 gap-x-7 gap-y-9 overflow-x-hidden">
            <?php foreach (getPosts("all") as $post): ?>
                <article class="flex flex-col shadow w-auto h-auto">
            <!-- Article Image -->
            <a href="../../public/views/viewpost.php?id=<?= $post["id"] ?>" class="hover:opacity-75">
                <img class="w-full h-2/3" src="<?= $post["photo"] ?>">
            </a>
            <div class="bg-white flex flex-col justify-start p-6">
                <a href="#" class="text-blue-700 text-sm font-bold uppercase pb-4">Technologie</a>
                <a href="../../public/views/viewpost.php?id=<?= $post["id"] ?>" class="text-3xl font-bold hover:text-gray-700 pb-4"><?= $post["title"] ?></a>
                <p href="../../public/views/viewpost.php?id=<?= $post["id"] ?>" class="text-sm pb-3">
                    Créé par <a href="#" class="font-semibold hover:text-gray-800"><?= getUser($post["createdBy"])["username"]  ?></a>, Publié le <?= $post["createdAt"] ?>
                </p>
                <a href="../../public/views/viewpost.php?id=<?= $post["id"] ?>" class="pb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus quis porta dui. Ut eu iaculis massa. Sed ornare ligula lacus, quis iaculis dui porta volutpat. In sit amet posuere magna..</a>
                <!-- <a href="#" class="uppercase text-gray-800 hover:text-black">Afficher plus <i class="fas fa-arrow-right"></i></a> -->
            </div>
        </article>
        <?php endforeach; ?>
    </section>

    <!-- $lastId = $db->query("SELECT id FROM posts ORDER BY id desc limit 1")->fetchColumn(); 

    if ($lastId === null) {
        $lastId = 0;
    } else {
        $reference = "ALB" . str_pad($lastId + 1, 4, "0", STR_PAD_LEFT);
    }

    $reference = "ALB" . str_pad($lastId + 1, 4, "0", STR_PAD_LEFT);-->
</body>
</html>
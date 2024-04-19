<?php

$categorys = getCategory(-1);
$post

?>

<div class="container max-w-screen gap-y-3 overflow-y-hidden min-h-screen flex flex-wrap py-6">
    <?php foreach($categorys as $category): ?>
        <section class="w-[80%] h-fit rounded-lg shadow-md  max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden">
            <div class="mb-2 flex flex-row items-center justify-between">
                <h1 class="text-lg text-black font-bold"><a href="/index.php?page=viewCategory&ref=<?=$category["reference"]?>" class="inline-flex items-center"><i class="fa-solid fa-<?= $category["icons"]?> bg-indigo-500 text-white p-2 rounded-full shadow-md  mr-3"></i><?= ucfirst($category["name"]) ?></a></h1>
                <a href="/index.php?page=viewCategory&ref=<?=$category["reference"]?>" class="text-sm font-sans font-medium text-indigo-600 hover:text-indigo-500 transition-all">Voir plus</a>
            </div>
            <div>
                <?php print_r(getPostsWhereCat($category["id"], 3, "desc")); ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>
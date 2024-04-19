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
            <div class="space-y-2">
                <?php $postsCategory = getPostsWhereCat($category["id"], 3, "desc"); foreach ($postsCategory as $postCategory): ?>
                    <article class="border-b border-spacing-0 h-16 m-0 px-3">
                        <div class="flex flex-row items-center justify-between gap-x-3">
                            <a href="index.php?page=viewpost&ref=<?= $postCategory["reference"]?>">
                                <div class="group">
                                    <h2 class="text-lg font-bold text-black group-hover:text-indigo-600 transition-all duration-75"><?= ucfirst($postCategory["title"]) ?></h2>
                                    <p class="text-sm text-gray-500 group-hover:text-gray-400 transition-all duration-75"><?= substr($postCategory["description"], 0, 60) ?>...</p>
                                </div>
                            </a>
                            <div class="flex flex-row items-center gap-x-8">
                                <div class="flex flex-col w-fit h-fit items-center gap-x-2">
                                    <p class="text-sm text-gray-500"><?= getNbComments($postCategory["id"])["nbComments"] ?></p>
                                    <span class="text-sm text-gray-400">Messages</span>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <div class="flex flex-row items-center gap-x-2">
                                        <i class="fa-solid fa-user text-gray-500"></i>
                                        <p class="text-sm text-gray-500"><?= getUser($postCategory["createdBy"])["username"] ?></p>
                                    </div>
                                    <div class="flex flex-row items-center gap-x-2">
                                        <i class="fa-solid fa-calendar text-gray-500"></i>
                                        <p class="text-sm text-gray-500"><?= date("d/m/Y", strtotime($postCategory["createdAt"])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>
<?php

if (!isset($_GET["ref"])) {
    header("Location: /index.php?page=home");
} elseif (!getCategoryByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$category = getCategoryByRef($_GET["ref"]);

?>

<div class="h-screen">
    <section class="w-[80%] h-[80%] mt-[5%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden dark:bg-slate-700">
        <div class="mb-2 flex flex-row border-b-2 h-16 items-center justify-between">
            <h2 class="text-lg text-black font-bold dark:text-slate-200"><a href="/index.php?page=home" class="inline-flex items-center"><i class="fa-solid fa-<?= $category["icons"]?> bg-indigo-500 text-white p-2 rounded-full shadow-md  mr-3"></i><?= ucfirst($category["name"]) ?></a></h2>
            <?php if(isset($_SESSION["user"])): ?>
                <a href="/public/views/insert_post.php?cat=<?= $category["id"] ?>" type="button" class="text-sm font-sans font-medium text-indigo-500 rounded-md px-2.5 py-3 hover:text-indigo-500 transition-all">Créer un post</a>
            <?php endif; ?>
        </div>
        <div class="space-y-2">
            <?php $postsCategory = getPostsWhereCat($category["id"], -1, "desc"); foreach ($postsCategory as $postCategory): ?>
                <article class="border-b border-spacing-0 h-16 m-0 px-3">
                    <div class="flex flex-row items-center justify-between gap-x-3">
                        <a href="index.php?page=viewpost&ref=<?= $postCategory["reference"]?>">
                            <div class="group">
                                <h2 class="text-lg font-bold text-black group-hover:text-indigo-600 transition-all duration-75 dark:text-slate-200"><?= ucfirst($postCategory["title"]) ?></h2>
                                <p class="text-sm text-gray-500 group-hover:text-gray-400 transition-all duration-75 dark:text-slate-300"><?= substr($postCategory["description"], 0, 60) ?>...</p>
                            </div>
                        </a>
                        <div class="flex flex-row items-center gap-x-8">
                            <div class="flex flex-col w-fit h-fit items-center gap-x-2">
                                <p class="text-sm text-gray-500 dark:text-slate-200"><?= getNbComments($postCategory["id"])["nbComments"] ?></p>
                                <span class="text-sm text-gray-400 dark:text-slate-200">Messages</span>
                            </div>
                            <div class="flex flex-col gap-y-2">
                                <div class="flex flex-row items-center gap-x-2 group">
                                    <i class="fa-solid fa-user text-gray-500 transition-all duration-75 dark:text-slate-300"></i>
                                    <a href="index.php?page=profil&ref=<?= getUser($postCategory["createdBy"])["reference"] ?>" class="text-sm text-gray-500 font-medium group-hover:text-indigo-500 transition duration-150 dark:text-slate-300"><?= getUser($postCategory["createdBy"])["username"] ?></a>
                                </div>
                                <div class="flex flex-row items-center gap-x-2">
                                    <i class="fa-solid fa-calendar text-gray-500 dark:text-slate-300"></i>
                                    <p class="text-sm text-gray-500 dark:text-slate-300"><?= date("d/m/Y", strtotime($postCategory["createdAt"])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>
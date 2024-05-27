<?php

$categorys = getCategory(-1);

?>

<div class="container mx-auto max-w-screen w-fit gap-y-3 overflow-y-hidden min-h-screen flex flex-wrap py-6">
    <?php foreach ($categorys as $category) : ?>
        <section class="w-[90%] lg:w-[80%] xl:w-[80%] h-fit rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden dark:bg-slate-700">
            <div class="mb-2 flex flex-row border-b-2 h-16 items-center justify-between">
                <h1 class="text-lg text-black font-bold dark:text-slate-200"><a href="/index.php?page=viewCategory&ref=<?= $category["reference"] ?>" class="inline-flex items-center"><i class="fa-solid fa-<?= $category["icons"] ?> bg-indigo-500 text-white p-2 rounded-full shadow-md  mr-3"></i><?= ucfirst($category["name"]) ?></a></h1>
                <a href="/index.php?page=viewCategory&ref=<?= $category["reference"] ?>" class="text-sm font-sans font-medium text-indigo-600 hover:text-indigo-500 transition-all">Voir plus</a>
            </div>
            <div class="space-y-1">
                <?php $postsCategory = getPostsWhereCat($category["id"], 3, "desc");
                if (!$postsCategory) {
                    echo "<div class='w-full text-center'><span class='w-full italic text-sm text-center text-gray-500 dark:text-slate-300'>Il n'y a aucun post dans cette catégorie.</span></div>";
                };
                foreach ($postsCategory as $postCategory) : ?>
                    <article class="border-b w-full border-spacing-0 h-16 m-0 px-3">
                        <div class="flex flex-row items-center justify-between gap-x-3">
                            <a class="2xl:w-2/3 xl:w-2/3 lg:w-1/2" href="index.php?page=post&ref=<?= $postCategory["reference"] ?>">
                                <div class="group duration-75">
                                    <h2 class="text-lg font-bold text-black dark:text-slate-200 group-hover:text-indigo-500 transition-all duration-75"><?= ucfirst($postCategory["title"]) ?></h2>
                                    <p class="hidden lg:block xl:block text-sm text-gray-500 group-hover:text-gray-600 transition-all duration-75 dark:text-slate-300"><?= substr($postCategory["description"], 0, 40) ?>...</p>
                                </div>
                            </a>
                            <div class="flex flex-row w-1/2 xl:w-1/3 2xl:w-1/3 items-center gap-x-8">
                                <div class="hidden lg:flex flex-col w-fit h-fit items-center gap-x-2">
                                    <p class="text-sm text-gray-600 dark:text-slate-300"><?= getNbComments($postCategory["id"])["nbComments"] ?></p>
                                    <span class="text-sm text-gray-300">Messages</span>
                                </div>
                                <div class="flex flex-col min-w-full w-full gap-y-2">
                                    <div class="flex flex-row items-center gap-x-2 group">
                                        <i class="fa-solid fa-user text-gray-500 transition-all duration-75 dark:text-slate-300"></i>
                                        <a href="index.php?page=profil&ref=<?= getUser($postCategory["createdBy"])["reference"] ?>" class="text-sm text-gray-500 font-medium group-hover:text-indigo-500 transition duration-150 dark:text-slate-300"><?= getUser($postCategory["createdBy"])["username"] ?></a>
                                    </div>
                                    <div class="flex flex-row items-center gap-x-2">
                                        <i class="fa-solid fa-calendar text-gray-500 dark:text-slate-300"></i>
                                        <p class=" text-sm text-gray-500 dark:text-slate-300"><?= date("d/m/Y", strtotime($postCategory["createdAt"])) ?></p>
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
<?php

if(!isset($_GET["ref"])) {
    header("Location: /index.php?page=home");
} elseif (!getPostByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$post = getPostByRef($_GET["ref"]);

print_r($post);

?>

<div class="h-screen">
    <div class="w-[80%] h-[80%] mt-[5%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden">
        <div class="space-y-2">
            <div class="*:min-w-full *:h-96 *:rounded-lg">
                <?php if (!$post["photo"] == ""): ?>
                    <img src="<?= $post["photo"] ?>" alt="">
                <?php else: ?>
                    <img src="https://via.placeholder.com/600" alt="">
                <?php endif; ?>
            </div>
            <div class="space-y-2">
                <span>
                    <h2 class="mt-2 text-3xl text-gray-800 font-bold"><?= ucfirst($post["title"])?></h2>
                    <span class="uppercase text-indigo-500 text-sm font-bold tracking-wide"><?= getCategory($post["postCategoryId"])["name"] ?></span>
                </span>
                <p class="text-gray-700 text-md font-normal text-justify tracking-wide text-wrap"><?= $post["description"] ?></p>
                <span class="text-gray-600 text-sm italic">Publié le <?= date("d/m/Y", strtotime($post["createdAt"])) ?> à <?= date("H:i", strtotime($post["createdAt"])) ?></span>
            </div>
            <div class="flex flex-col flex-nowrap text-justify ">
                <span class="text-gray-800 font-medium text-normal">Publié par,</span>
            </div>
        </div>
    </div>
</div>
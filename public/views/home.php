<?php

$categorys = getCategory(-1);

?>

<div class="container max-w-screen gap-y-3 overflow-y-hidden min-h-screen flex flex-wrap py-6">
    <?php foreach($categorys as $category): ?>
        <section class="w-[80%] h-fit rounded-lg shadow-md max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden">
            <div class="mb-2">
                <h1 class="text-lg text-black font-bold"><?= ucfirst($category["name"]) ?></h1>
                <a href="">Voir plus</a>
            </div>
            <div>
                <article>Art1</article>
                <article>Art2</article>
                <article>Art3</article>
            </div>
        </section>
    <?php endforeach; ?>
</div>
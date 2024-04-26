<?php

if(!isset($_GET["ref"])) {
    header("Location: /index.php?page=home");
} elseif (!getPostByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$post = getPostByRef($_GET["ref"]);
$userCreator = getUser($post["createdBy"]);

?>

<div class="h-screen">
    <div class="w-[80%] min-h-lvh h-[80%] mt-[5%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden">
        <div class="space-y-5">
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
                <p class="text-gray-700 text-md font-normal text-pretty text-justify tracking-wide"><?= $post["description"] ?></p>
                <span class="text-gray-600 text-sm italic">Publié le <?= date("d/m/Y", strtotime($post["createdAt"])) ?> à <?= date("H:i", strtotime($post["createdAt"])) ?></span>
            </div>
            <div class="flex flex-col flex-nowrap text-justify ">
                <span class="text-gray-700 text-sm font-medium text-left text-normal mb-2">Créer par,</span>
                <a href="/index.php?page=profil&ref=<?= $userCreator["reference"] ?>">
                    <div class="flex items-center gap-4 bg-white px-2 py-3 w-fit group hover:scale-105 hover:bg-gray-50 rounded-lg transition-all duration-75">
                        <?php if(isset($userCreator["image"]) && $userCreator["image"] != ""): ?>
                            <img src="<?= $userCreator["image"]?>" alt="avatar" class="relative shadow-sm-light inline-block object-cover object-center w-12 h-12 rounded-lg" />
                        <?php else: ?>
                            <span class="bg-gray-500 flex items-center text-3xl shadow-sm-light rounded-lg text-white w-12 h-12 justify-center">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        <?php endif; ?>
                        <div>
                            <h6 class="block font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-inherit">
                                <?= $userCreator["username"]?>
                            </h6>
                            <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-700">
                                <?= getRole($userCreator["roleId"])["name"]?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="mt-10">
            <h3 class="text-xl font-bold">Commentaires :</h3>
        </div>
    </div>
</div>
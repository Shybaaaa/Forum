<?php

if(!isset($_GET["ref"])) {
    header("Location: /index.php?page=home");
} elseif (!getUserByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$user = getUserByRef($_GET["ref"]);

//print_r($user);

?>

<div class="h-screen">
    <div class="w-[80%] h-[90%] mt-[2%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 overflow-x-hidden">
        <div class="w-full h-[20%] bg-gray-200 rounded-md"></div>
        <div class="-translate-y-1/2">
            <div class="flex flex-row items-center justify-center gap-x-4">
                <div class="w-[15%] h-[15%] flex flex-col space-y-3 items-center">
                    <?php if(isset($user["image"]) && $user["image"] != ""): ?>
                        <img src="<?= $user["image"] ?>" alt="avatar" class="relative shadow-md border-2 inline-block object-cover object-center h-[160px] w-[160px] min-w-full min-h-full rounded-full" />
                    <?php else: ?>
                        <span class="bg-gray-400 flex items-center text-8xl shadow-md rounded-full text-white min-w-full min-h-full justify-center">
                            <i class="fa-solid p-8 fa-user"></i>
                        </span>
                    <?php endif; ?>
                    <div class="inline-flex text-center flex-col items-center ">
                        <span class="text-2xl font-semibold"><?= $user["username"] ?></span>
                        <?php
                            switch (getRole($user["roleId"])["name"]) {
                                case "Fondateur":
                                    echo "<span class='bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300'>Fondateur</span>";
                                    break;
                                case "Administrateur":
                                    echo "<span class='bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300'>Administrateur</span>";
                                    break;
                                case "Modérateur":
                                    echo "<span class='bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300'>Modérateur</span>";
                                    break;
                                case "Membre":
                                    echo "<span class='bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300'>Membre</span>";
                                    break;
                                default:
                                    echo "<span class='text-sm text-gray-500'>Utilisateur</span>";
                                    break;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="-translate-y-[60%]">
            <div class="flex w-full items-center space-y-3 justify-center flex-col">
                <h3 class="text-lg font-semibold text-gray-600 w-[90%] py-2 text-left">Stats :</h3>
                <div class="flex flex-row justify-evenly w-full items-center gap-x-4 *:bg-gray-50/30 *:shadow-md *:p-4 *:rounded-lg *:w-40">
                    <div class="flex flex-col items-center gap-y-2">
                        <span class="text-2xl font-semibold text-indigo-600"><?= getNbPosts($user["id"])["nbPosts"] ?></span>
                        <span class="text-sm text-gray-500">Publications</span>
                    </div>
                    <div class="flex flex-col items-center gap-y-2">
                        <span class="text-2xl font-semibold text-indigo-600"><?= getNbComments($user["id"])["nbComments"] ?></span>
                        <span class="text-sm text-gray-500">Commentaires</span>
                    </div>
                    <div class="flex flex-col items-center gap-y-2">
                        <span class="text-2xl font-semibold text-indigo-600">0</span>
                        <span class="text-sm text-gray-500">Suivis</span>
                    </div>
                    <div class="flex flex-col items-center gap-y-2">
                        <span class="text-2xl font-semibold text-indigo-600">0</span>
                        <span class="text-sm text-gray-500">Abonnés</span>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="flex w-full items-center space-y-3 justify-center flex-col">
                <h3 class="text-lg font-semibold text-gray-600 w-[90%] py-2 text-left">Dernières publications :</h3>
                <div class="flex flex-col w-full items-center gap-y-4">
                    <?php $posts = getPostsByUser($user["id"], 2, "desc"); if (!$posts){ echo "<div class='w-full text-center'><span class='w-full italic text-sm text-center text-gray-500'>Il n'y a aucune publication.</span></div>";} ; foreach ($posts as $post): ?>
                        <div class="flex flex-row items-center justify-between gap-x-3 w-[90%] bg-gray-50/30 shadow-md p-4 rounded-lg">
                            <a href="index.php?page=viewpost&ref=<?= $post["reference"]?>">
                                <div class="group">
                                    <h2 class="text-lg font-bold text-black group-hover:text-indigo-600 transition-all duration-75"><?= ucfirst($post["title"]) ?></h2>
                                    <p class="text-sm text-gray-500 group-hover:text-gray-400 transition-all duration-75"><?= substr($post["description"], 0, 60) ?>...</p>
                                </div>
                            </a>
                            <div class="flex flex-row items-center gap-x-8">
                                <div class="flex flex-col w-fit h-fit items-center gap-x-2">
                                    <p class="text-sm text-gray-600"><?= getNbComments($post["id"])["nbComments"] ?></p>
                                    <span class="text-sm text-gray-400">Messages</span>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <div class="flex flex-row items-center gap-x-2 group">
                                        <i class="fa-solid fa-user text-gray-500 transition-all duration-75"></i>
                                        <a href="index.php?page=profil&ref=<?= getUser($post["createdBy"])["reference"] ?>" class="text-sm text-gray-500 font-medium group-hover:text-indigo-500 transition duration-150"><?= getUser($post["createdBy"])["username"] ?></a>
                                    </div>
                                    <div class="flex flex-row items-center gap-x-2">
                                        <i class="fa-solid fa-calendar text-gray-500"></i>
                                        <p class="text-sm text-gray-500"><?= date("d/m/Y", strtotime($post["createdAt"])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
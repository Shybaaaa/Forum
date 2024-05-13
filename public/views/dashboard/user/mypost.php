<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["search"])) {
    $posts = searchPost($_POST["search"]);
    print_r($posts);
} else {
    $posts = getPostUser($_SESSION["user"]["id"], "all" , "true");

}

?>  

<div class="w-10/12 h-[85%] shadow bg-white px-3.5 rounded-lg py-2.5">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <div class="flex flex-row m-2 my-3 justify-between">

                <form action="" method="POST">

                    <div class="relative mb-3 mr-5 float-left">
                        <label for="inputSearch" class="sr-only">Rechercher</label>
                        <input id="inputSearch" type="text" placeholder="Recherche..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400"/>
                        <button type="submit" name="search">
                            <i class="fa-solid fa-search text-gray-700 absolute top-3 left-3"></i>
                        </button>
                    </div>
                </form>
                <div class="relative m-[2px] mb-3 float-right sm:block">
                    <a href="../../../public/views/insert_post.php" type="button" class="bg-indigo-500 py-2.5 px-2 text-white rounded-lg font-medium hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                        <i class="fa-solid fa-circle-plus text-sm text-white mr-1"></i>
                        Créer un post
                    </a>
                </div>
            </div>
            <table class="min-w-full text-left text-xs whitespace-nowrap">
                <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600">
                <tr>
                    <th scope="col" class="px-6 py-5">
                        Titre
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Catégorie
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Commentaires
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    <tr class="border-b dark:border-neutral-600">
                        <th scope="row" class="px-6 py-5"><?= $post["title"] ?> <a href="/index.php?page=viewpost&ref=<?= $post["reference"] ?>" title="Vers le post"> <i class="fa-solid fa-up-right-from-square"></i></a></th>
                        <td class="px-6 py-5"><?= ucfirst(getCategory($post["postCategoryId"])["name"]) ?></td>
                        <td class="px-6 py-5">
                            <?php if ($post["status"] == "a"): ?>
                                <span class="text-green-500 font-bold text-sm">En ligne</span>
                            <?php elseif ($post["status"] == "b"): ?>
                                <span class="text-orange-300 font-bold text-sm">Masqué</span>
                            <?php elseif ($post["status"]  == "c"): ?>
                                <span class="text-red-600 font-bold text-sm">Supprimé</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5"><?= getNbComments($post["id"])["nbComments"] ?></td>
                        <td class="px-6 py-5 flex flex-row gap-x-3 *:text-sm">
                            <?php if ($post["isDeleted"]): ?>
                                <button disabled title="Désactivé"><i class="fa-solid fa-eye-slash text-gray-200"></i></button>
                            <?php else: ?>
                                <?php if($post["isActive"]): ?>
                                    <button><i  class="fa-solid fa-eye text-green-500"></i></button>
                                <?php else: ?>
                                    <button><i  class="fa-solid fa-eye-slash text-orange-400"></i></button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <a href="index.php?page=editPost&ref=<?= $post["reference"] ?>" ><i title="Modifier" data-row-update="<?= $post["reference"]?>" class="fa-solid fa-pen-to-square text-gray-600"></i></a>

                            <?php if(!$post["isDeleted"]): ?>
                                <button data-modal-target="modalRestore" data-modal-toggle="modalRestore"><i title="Supprimé" class="fa-solid fa-trash text-red-600"></i></button>
                            <?php else: ?>
                                <button value="<?= $post["id"]?>"><i title="Restaurer" class="fa-solid fa-trash-restore text-green-500"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center italic text-gray-500 py-5">Vous n'avez encore posté aucun poste.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalRestore" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalRestore">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Fermer la popup</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this product?</h3>
                <button data-modal-hide="modalRestore" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, I'm sure
                </button>
                <button data-modal-hide="modalRestore" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
            </div>
        </div>
    </div>
</div>

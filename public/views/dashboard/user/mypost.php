<?php

$posts = getPostUser($_SESSION["user"]["id"], "all", "true");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST) {
        case isset($_POST["modalDeletePost"]):
            $id = $_POST["deleteModalInput"];
            deletePost($id, $_SESSION["user"]["id"]);
            break;
        case isset($_POST["modalRestorePost"]):
            $id = $_POST["deleteModalInput"];
            restorePost($id, $_SESSION["user"]["id"]);
            break;
        case isset($_POST["modalHidePost"]):
            $id = $_POST["hideModalInput"];
            hidePost($id, $_SESSION["user"]["id"]);
            break;
        case isset($_POST["modalShowPost"]):
            $id = $_POST["showModalInput"];
            showPost($id, $_SESSION["user"]["id"]);
            break;
        case isset($_POST["search"]):
            $search = $_POST["inputSearch"];
            $posts = getPostUser($_SESSION["user"]["id"], "all", "true", $search);
            break;
    }


    //    $pdo = dbConnect();
    //
    //    $stmt = $pdo->prepare("SELECT * FROM posts");
    //    $stmt->execute();
    //    $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
    //    if(isset($_GET['q']) and !empty($_GET['q'])){
    //
    //        $value = $_GET['q'];
    //
    //        $stmt = $pdo->prepare("SELECT * FROM posts WHERE CONCAT(tilte, postCategoryId) LIKE '%".$value."%'");
    //        $stmt->execute();
    //        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    //    }
}

?>

<div class="md:w-45 w-10/12 h-[85%] shadow bg-white px-3.5 rounded-lg py-2.5 dark:bg-slate-700">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <div class="flex flex-row m-2 my-3 justify-between">

                <form action="" method="POST">

                    <div class="relative mb-3 mr-5 float-left">
                        <label for="inputSearch" class="sr-only">Rechercher</label>
                        <input id="inputSearch" type="text" placeholder="Recherche..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400" />
                        <button type="submit" name="search">
                            <i class="fa-solid fa-search text-gray-700 absolute top-3 left-3 dark:text-slate-400"></i>
                        </button>
                    </div>
                </form>
                <div class="relative m-[2px] mb-3 float-right sm:block">
                    <a href="?page=addPost" type="button" class="bg-indigo-500 py-2.5 px-2 text-white rounded-lg font-medium hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                        <i class="fa-solid fa-circle-plus text-sm text-white mr-1"></i>
                        Créer un post
                    </a>
                </div>
            </div>
            <table class="min-w-full text-left text-xs whitespace-nowrap dark:text-slate-200">
                <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-300">
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
                    <?php if ($posts) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <tr class="border-b dark:border-neutral-300">

                                <?php if (!$post["isDeleted"]) : ?>
                                    <th scope="row" class="px-6 py-5"><?= $post["title"] ?> <a target="_blank" href="/index.php?page=viewpost&ref=<?= $post["reference"] ?>" title="Vers le post"> <i class="fa-solid fa-up-right-from-square"></i></a></th>
                                <?php else : ?>

                                    <th scope="row" class="px-6 py-5"><?= $post["title"] ?></th>
                                <?php endif; ?>
                                <td class="px-6 py-5"><?= ucfirst(getCategory($post["postCategoryId"])["name"]) ?></td>
                                <td class="px-6 py-5">
                                    <?php if ($post["status"] == "a") : ?>
                                        <span class="text-green-500 font-bold text-sm dark:text-green-400">En ligne</span>
                                    <?php elseif ($post["status"] == "b") : ?>
                                        <span class="text-orange-300 font-bold text-sm dark:text-orange-200">Masqué</span>
                                    <?php elseif ($post["status"]  == "c") : ?>
                                        <span class="text-red-600 font-bold text-sm dark:text-red-500">Supprimé</span>
                                    <?php elseif ($post["status"]  == "d") : ?>
                                        <span class="text-red-700 font-bold text-sm dark:text-red-500">Désactivé</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5"><?= getNbComments($post["id"])["nbComments"] ?></td>
                                <td class="px-6 py-5 flex flex-row gap-x-3 *:text-sm">
                                    <?php if ($post["isDeleted"]) : ?>
                                        <button disabled title="Désactivé"><i class="cursor-not-allowed fa-solid fa-eye-slash text-gray-200"></i></button>
                                    <?php else : ?>
                                        <?php if ($post["isActive"]) : ?>
                                            <button onclick="renderModalHidePost(<?= $post["id"]; ?>, '<?= $post["reference"] ?>')" data-modal-target="hideModal" data-modal-show="hideModal"><i class="fa-solid fa-eye text-green-500"></i></button>
                                        <?php else : ?>
                                            <button onclick="renderModalShowPost(<?= $post["id"]; ?>, '<?= $post["reference"] ?>')" data-modal-target="showModal" data-modal-show="showModal"><i class="fa-solid fa-eye-slash text-orange-400"></i></button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!$post["isDeleted"]) : ?>
                                        <a href="index.php?page=editPost&ref=<?= $post["reference"] ?>"><i title="Modifier" data-row-update="<?= $post["reference"] ?>" class="fa-solid fa-pen-to-square text-gray-600"></i></a>
                                    <?php else : ?>
                                        <button disabled><i title="Modifier" data-row-update="<?= $post["reference"] ?>" class="cursor-not-allowed fa-solid fa-pen-to-square text-gray-200"></i></button>
                                    <?php endif; ?>
                                    <?php if ($post["status"] === "d") : ?>
                                        <button disabled><i title="Supprimé" class="cursor-not-allowed fa-solid fa-trash text-gray-200"></i></button>
                                    <?php else : ?>
                                        <?php if (!$post["isDeleted"]) : ?>
                                            <button onclick="renderModalDeletePost(<?= $post["id"]; ?>, '<?= $post["reference"] ?>')" class="btnDelete" data-modal-target="deleteModal" data-modal-show="deleteModal"><i title="Supprimé" class="fa-solid fa-trash text-red-600"></i></button>
                                        <?php else : ?>
                                            <button data-modal-target="restoreModal" data-modal-show="restoreModal" onclick="renderModalRestorePost(<?= $post["id"]; ?>, '<?= $post["reference"] ?>')" value="<?= $post["id"] ?>"><i title="Restaurer" class="fa-solid fa-trash-restore text-green-500"></i></button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center italic text-gray-500 py-5 dark:text-slate-300">Vous n'avez encore posté aucun poste.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="deleteModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Fermer la popup</span>
            </button>
            <form action="" enctype="multipart/form-data" method="post">
                <div class="space-y-3 p-4 md:p-5 text-center dark">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 id="deleteModalH3" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"></h3>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-800 font-semibold text-left mb-1" for="deleteModalInput">Post :</label>
                        <input type="text" id="deleteModalInput" readonly name="deleteModalInput" placeholder="Id" class="cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    </div>
                    <div class="flex flex-row align-middle justify-evenly">
                        <input data-modal-hide="deleteModal" name="modalDeletePost" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Oui, supprimer !">
                        <button data-modal-hide="deleteModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="restoreModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="restoreModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Fermer la popup</span>
            </button>
            <form action="" enctype="multipart/form-data" method="post">
                <div class="space-y-3 p-4 md:p-5 text-center dark">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 id="deleteModalH3" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"></h3>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-800 font-semibold text-left mb-1" for="deleteModalInput">Post :</label>
                        <input type="text" id="restoreModalInput" readonly name="deleteModalInput" placeholder="Id" class="cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    </div>
                    <div class="flex flex-row align-middle justify-evenly">
                        <input data-modal-hide="restoreModal" name="modalRestorePost" type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Oui, restaurer !">
                        <button data-modal-hide="restoreModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="hideModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="hideModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Fermer la popup</span>
            </button>
            <form action="" enctype="multipart/form-data" method="post">
                <div class="p-4 md:p-5 text-center dark">
                    <i class="fa-solid fa-eye-slash text-center mb-4 text-gray-400 w-12 h-12 text-4xl"></i>
                    <input type="text" id="hideModalInput" readonly name="hideModalInput" placeholder="Id" class="hidden sr-only cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    <div class="flex flex-row items-center">
                        <h3 id="hideModalH3" class="font-normal text-sm text-center w-8/12 h-full text-gray-500 dark:text-gray-400"></h3>
                        <input data-modal-hide="hideModal" name="modalHidePost" type="submit" class="text-white w-3/12 bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Masquer">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="showModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="showModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Fermer la popup</span>
            </button>
            <form action="" enctype="multipart/form-data" method="post">
                <div class="p-4 md:p-5 text-center dark">
                    <i class="fa-solid fa-eye text-center mb-4 text-gray-400 w-12 h-12 text-4xl"></i>
                    <input type="text" id="showModalInput" readonly name="showModalInput" placeholder="Id" class="hidden sr-only cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    <div class="flex flex-row items-center">
                        <h3 id="hideModalH3" class="font-normal text-sm text-center w-8/12 h-full text-gray-500 dark:text-gray-400"></h3>
                        <input data-modal-hide="showModal" name="modalShowPost" type="submit" class="text-white w-3/12 bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Afficher">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="application/javascript" src="/public/js/mypostModal.js"></script>
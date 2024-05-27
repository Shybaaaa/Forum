<?php
require_once "../../../private/functions/functions.php";

$categorys = getCategory(-1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST) {
        case isset($_POST["createCategory"]):
            addCategory($_POST["createCategory"], $_SESSION["user"]["id"], $_POST["icon"]);
            break;
        case isset($_POST["search"]):
            $search = $_POST["inputSearch"];
            $categorys = searchCategory($_POST["inputSearch"]);
            break;
        case isset($_POST["modalDeleteCat"]):
            $id = $_POST["deleteModalInput"];
            deleteCatAdmin($id, $_SESSION["user"]);
            break;
        case isset($_POST["modalRestoreCat"]):
            $id = $_POST["deleteModalInput"];
            restoreCatAdmin($id, $_SESSION["user"]);
            break;
    }
}

?>

<div class="w-10/12 h-[80%] bg-white px-3.5 rounded-lg py-2.5 dark:bg-slate-700">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <form action="" method="POST">

                <div class="relative mb-3 mr-5 float-left">
                    <label for="inputSearch" class="sr-only">Rechercher</label>
                    <input id="inputSearch" name="inputSearch" type="text" placeholder="Recherche..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400" />
                    <button type="submit" name="search">
                        <i class="fa-solid fa-search text-gray-700 absolute top-3 left-3 dark:text-slate-400"></i>
                    </button>
                </div>
            </form>
            <div class="relative m-[2px] mb-3 float-right hidden sm:block">
                <button data-modal-target="addCategoryModal" data-modal-show="addCategoryModal" type="button" class="bg-indigo-500 py-2.5 px-2 text-white rounded-lg font-medium hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                    <i class="fa-solid fa-circle-plus text-sm text-white mr-1"></i>
                    Créer une catégorie
                </button>
            </div>
            <table class="min-w-full text-left text-xs whitespace-nowrap">
                <thead class="uppercase tracking-wider border-b-2 dark:text-slate-300">
                    <tr>
                        <th scope="col" class="px-6 py-5">
                            Titre
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Posts
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorys as $category) : ?>
                        <tr class="border-b dark:text-slate-300">
                            <th scope="row" class="px-6 py-5"><?= ucfirst($category["name"]) ?></th>
                            <td class="px-6 py-5"><?= getNbposts($category["id"])["nbPosts"] ?></td>
                            <td class="px-6 py-5 flex flex-row gap-x-3 *:text-sm">

                                <!-- <button><i title="Modifier" class="fa-solid fa-pen-to-square text-gray-600"></i></button> -->

                                <?php if (!$category["isDeleted"]) : ?>
                                    <button onclick="renderModalDeleteCat(<?= $category["id"]; ?>, '<?= $category["reference"] ?>')" data-modal-target="deleteModal" data-modal-show="deleteModal"><i title="Supprimé" class="fa-solid fa-trash text-red-600"></i></button>
                                <?php else : ?>
                                    <button onclick="renderModalRestoreCat(<?= $category["id"]; ?>, '<?= $category["reference"] ?>')" data-modal-target="restoreModal" data-modal-show="restoreModal"><i title="Restaurer" class="fa-solid fa-trash-restore text-green-500"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addCategoryModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Créer une nouvelle catégories
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="addCategoryModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="" method="post" class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="createCategory" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                        <input type="text" name="createCategory" id="createCategory" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nom de la catégories" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="icon" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Icon</label>
                        <input type="text" name="icon" id="icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ex: tv" required="">
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Ajouter une catégorie
                </button>
            </form>
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
                        <label class="text-sm text-gray-800 font-semibold text-left mb-1 dark:text-gray-300" for="deleteModalInput">Catégorie :</label>
                        <input type="text" id="deleteModalInput" readonly name="deleteModalInput" placeholder="Id" class="cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    </div>
                    <div class="flex flex-row align-middle justify-evenly">
                        <input data-modal-hide="deleteModal" name="modalDeleteCat" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Oui, supprimer !">
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
                        <label class="text-sm text-gray-800 font-semibold text-left mb-1 dark:text-gray-300" for="deleteModalInput">Catégorie :</label>
                        <input type="text" id="restoreModalInput" readonly name="deleteModalInput" placeholder="Id" class="cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    </div>
                    <div class="flex flex-row align-middle justify-evenly">
                        <input data-modal-hide="restoreModal" name="modalRestoreCat" type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Oui, restaurer !">
                        <button data-modal-hide="restoreModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/public/js/adminCategory.js"></script>
<?php
require_once "../../../private/functions/functions.php";

$categorys = getCategory(-1);

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    switch ($_POST){
        case isset($_POST["createCategory"]):
            addCategory($_POST["createCategory"], $_SESSION["user"]["id"]);
            break;
    }
}

?>

<div class="w-10/12 h-[80%] bg-white px-3.5 rounded-lg py-2.5 dark:bg-slate-700">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <div class="relative m-[2px] mb-3 mr-5 float-left">
                <label for="inputSearch" class="sr-only">Rechercher</label>
                <input id="inputSearch" type="text" placeholder="Recherche..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400"/>
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-200">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            </div>
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
                    <!-- <th scope="col" class="px-6 py-5">
                        Catégorie
                    </th> -->
                    <!-- <th scope="col" class="px-6 py-5">
                        Status
                    </th> -->
                    <th scope="col" class="px-6 py-5">
                        Posts
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categorys as $category): ?>
                    <tr class="border-b dark:text-slate-300">
                        <th scope="row" class="px-6 py-5"><?= ucfirst($category["name"]) ?></th>
                        <td class="px-6 py-5"><?= getNbposts($category["id"])["nbPosts"] ?></td>
                        <td class="px-6 py-5 flex flex-row gap-x-3 *:text-sm">
                            <?php if ($category["isDeleted"]): ?>
                                <button disabled title="Désactivé"><i class="fa-solid fa-eye-slash text-gray-200"></i></button>
                            <?php else: ?>
                                <?php if($category["isActive"]): ?>
                                    <button><i  class="fa-solid fa-eye text-green-500"></i></button>
                                <?php else: ?>
                                    <button><i  class="fa-solid fa-eye-slash text-orange-400"></i></button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <button><i title="Modifier" class="fa-solid fa-pen-to-square text-gray-600"></i></button>

                            <?php if(!$category["isDeleted"]): ?>
                                <button data-modal-target="modalRestaure" data-modal-hide="modalRestore"  value="<?= $category["id"]?>"><i title="Supprimé" class="fa-solid fa-trash text-red-600"></i></button>
                            <?php else: ?>
                                <button value="<?= $category["id"]?>"><i title="Restaurer" class="fa-solid fa-trash-restore text-green-500"></i></button>
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
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="" method="post" class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                        <input type="text" name="createCategory" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nom de la catégories" required="">
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Ajouter une catégorie
                </button>
            </form>
        </div>
    </div>
</div>
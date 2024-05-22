<?php
require_once "../../../private/functions/functions.php";

$users = getUser(-1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST) {
        case isset($_POST["search"]):
            $search = $_POST["inputSearch"];
            $users = searchUser($_POST["inputSearch"]);
            break;

        case isset($_POST["modalBanUser"]):
            banUser($_POST["banModalInput"], $_SESSION["user"]);
            break;
    }
}

?>

<div class="w-10/12 h-[80%] bg-white px-3.5 rounded-lg py-2.5 dark:bg-slate-700">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <div class="flex flex-row m-2 my-3 justify-between">
                <form action="" method="POST">

                    <div class="relative mb-3 mr-5 float-left">
                        <label for="inputSearch" class="sr-only">Rechercher</label>
                        <input id="inputSearch" name="inputSearch" type="text" placeholder="Recherche..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400" />
                        <button type="submit" name="search">
                            <i class="fa-solid fa-search text-gray-700 absolute top-3 left-3 dark:text-slate-400"></i>
                        </button>
                    </div>
                </form>
                <div class="relative m-[2px] mb-3 float-right sm:block">
                    <a href="#" type="button" class="bg-indigo-500 py-2.5 px-2 text-white rounded-lg font-medium hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                        <i class="fa-solid fa-circle-plus text-sm text-white mr-1"></i>
                        Créer un compte
                    </a>
                </div>
            </div>
            <table class="min-w-full text-left text-xs whitespace-nowrap">
                <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600">
                    <tr>
                        <th scope="col" class="px-6 py-5 dark:text-slate-200">
                            Nom
                        </th>
                        <th scope="col" class="px-6 py-5 dark:text-slate-200">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-5 dark:text-slate-200">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-5 dark:text-slate-200">
                            Posts
                        </th>
                        <th scope="col" class="px-6 py-5 dark:text-slate-200">
                            Commentaires
                        </th>
                        <th scope="col" class="px-6 py-5 dark:text-slate-200">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr class="border-b dark:text-slate-300">
                            <th scope="row" class="px-6 py-5"><?= $user["username"] ?></th>
                            <td class="px-6 py-5"><?= getRole($user["roleId"])["name"] ?></td>
                            <td class="px-6 py-5">
                                <?php if ($user["status"] == "a") : ?>
                                    <span class="text-green-500 font-bold text-sm">Actif</span>
                                <?php elseif ($user["status"] == "b") : ?>
                                    <span class="text-orange-300 font-bold text-sm">Désactivé</span>
                                <?php elseif ($user["status"]  == "c") : ?>
                                    <span class="text-red-600 font-bold text-sm">Banni</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-5"><?= ucfirst(getNbPosts($user["id"])["nbPosts"]) ?></td>
                            <td class="px-6 py-5"><?= getNbCommentsForUser($user["id"])["nbComments"] ?></td>
                            <td class="px-6 py-5 flex flex-row gap-x-3 *:text-sm">
                                <!-- <?php if ($user["isDeleted"]) : ?>
                                    <button disabled title="Désactivé"><i class="fa-solid fa-eye-slash text-gray-200"></i></button>
                                <?php else : ?>
                                    <?php if ($user["isActive"]) : ?>
                                        <button><i class="fa-solid fa-eye text-green-500"></i></button>
                                    <?php else : ?>
                                        <button><i class="fa-solid fa-eye-slash text-orange-400"></i></button>
                                    <?php endif; ?>
                                <?php endif; ?> -->

                                <button><i title="Modifier" class="fa-solid fa-pen-to-square text-gray-600"></i></button>

                                <!-- <?php if (!$user["isDeleted"]) : ?>
                                    <button data-modal-target="modalRestaure" data-modal-hide="modalRestore" value="<?= $user["id"] ?>"><i title="Supprimer" class="fa-solid fa-trash text-red-600"></i></button>
                                <?php else : ?>
                                    <button value="<?= $user["id"] ?>"><i title="Restaurer" class="fa-solid fa-trash-restore text-green-500"></i></button>
                                <?php endif; ?> -->

                                <?php if (!$user["isBanned"]) : ?>
                                    <button onclick="renderModalBanned(<?= $user["id"] ?>,'<?= $user["username"] ?>' )" data-modal-target="banModal" data-modal-show="banModal" value="<?= $user["id"] ?>"><i title="Bannir" class="fa-solid fa-gavel text-red-600"></i></button>
                                <?php else : ?>
                                    <button value="<?= $user["id"] ?>"> <i title="Debannir" class="fa-solid fa-gavel text-green-400"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="banModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="banModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Fermer la popup</span>
            </button>
            <form action="" enctype="multipart/form-data" method="post">
                <div class="p-4 md:p-5 text-center dark">
                    <i class="fa-solid fa-gavel text-center mb-4 text-gray-400 w-12 h-12 text-4xl"></i>
                    <input type="text" id="banModalInput" readonly name="banModalInput" class=" cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    <div class="flex flex-row items-center">
                        <h3 id="ModalH3" class="font-normal text-sm text-center w-8/12 h-full text-gray-500 dark:text-gray-400"></h3>
                        <input data-modal-hide="banModal" name="modalBanUser" id="modalBanUser" type="submit" class="text-white w-3/12 bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Bannir">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div id="banModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="banModal">
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
                        <h3 id="ModalH3" class="font-normal text-sm text-center w-8/12 h-full text-gray-500 dark:text-gray-400"></h3>
                        <input data-modal-hide="banModal" name="banUser" type="submit" class="text-white w-3/12 bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Afficher">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> -->

<script src="/public/js/adminUser.js"></script>
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
            banUser($_POST["banModalInput"], $_SESSION["user"], $_POST["reason"]);
            break;

        case isset($_POST["debanUser"]):
            unbanUser($_POST["debanModalInput"], $_SESSION["user"]);
            break;
    }
}

if ($_SESSION["user"]["roleId"] < 2) {
    newNotification("error", "Error 401", true, "fa-triangle-exclamation");
    header("Location: /index.php?page=home");
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

                                <!-- <button onclick="renderModalEditUser()" data-modal-target="editUserModal" data-modal-show="editUserModal"><i title="Modifier" class="fa-solid fa-pen-to-square text-gray-600"></i></button> -->

                                <?php if (!$user["isBanned"]) : ?>
                                    <button onclick="renderModalBanned(<?= $user["id"] ?>,'<?= $user["username"] ?>' )" data-modal-target="banModal" data-modal-show="banModal" value="<?= $user["id"] ?>"><i title="Bannir" class="fa-solid fa-gavel text-red-600"></i></button>
                                <?php else : ?>
                                    <button onclick="renderModalDeban(<?= $user["id"] ?>,'<?= $user["username"] ?>')" data-modal-target="debanModal" data-modal-show="debanModal" value="<?= $user["id"] ?>"> <i title="Debannir" class="fa-solid fa-gavel text-green-400"></i></button>
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
                <div class="flex flex-col items-center justify-between gap-y-2 p-4 md:p-5 text-center dark">
                    <i class="fa-solid fa-gavel text-center mb-4 text-gray-400 w-12 h-12 text-4xl"></i>
                    <h3 id="ModalH3" class="font-normal text-sm text-center w-8/12 h-full text-gray-500 dark:text-gray-400"></h3>
                    <input type="text" id="banModalInput" readonly name="banModalInput" class="sr-only hidden cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    <div class="flex flex-col w-10/12 ">
                        <label for="reason" class="text-gray-500 font-medium text-sm text-left ">Raison :</label>
                        <input id="reason" class="rounded-lg border bg-gray-50 active:ring-indigo-600 duration-75 transition " name="reason" required type="text" placeholder="Raison">
                    </div>
                    <input data-modal-hide="banModal" name="modalBanUser" id="modalBanUser" type="submit" class="text-white w-fit bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Bannir">
                </div>
            </form>
        </div>
    </div>
</div>

<div id="debanModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="debanModal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Fermer la popup</span>
            </button>
            <form action="" enctype="multipart/form-data" method="post">
                <div class="p-4 md:p-5 text-center dark">
                    <i class="fa-solid fa-rotate text-center mb-4 text-gray-400 w-12 h-12 text-4xl"></i>
                    <input type="text" id="debanModalInput" readonly name="debanModalInput" placeholder="Id" class="hidden sr-only cursor-not-allowed text-gray-700 bg-gray-100 border border-gray-400 px-2 py-2 rounded-lg">
                    <div class="flex flex-row items-center">
                        <h3 id="ModalH3" class="font-normal text-sm text-center w-8/12 h-full text-gray-500 dark:text-gray-400"></h3>
                        <input data-modal-hide="debanModal" name="debanUser" type="submit" class="text-white w-3/12 bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" value="Déban">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<section id="editUserModal" tabindex="-1" class="bg-white dark:bg-gray-900 hidden -translate-x-full overflow-y-auto overflow-x-hidden fixed top-0 left-0 z-50 justify-center items-center w-1/3 md:inset-0 h-[calc(100%-1rem)] max-h-full duration-100 transition">
    <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
        <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="editUserModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Fermer la popup</span>
        </button>
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Modifier l'utilisateur</h2>
        <form action="#">
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="Apple iMac 27&ldquo;" placeholder="Type product name" required="">
                </div>
                <div>
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Roles</label>
                    <select id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <?php foreach (getRole(-1) as $role) : ?>
                            <option value="<?= $role["id"] ?>"><?= $role["name"] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="item-weight" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item Weight (kg)</label>
                    <input type="number" name="item-weight" id="item-weight" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="15" placeholder="Ex. 12" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Write a product description here...">Standard glass, 3.8GHz 8-core 10th-generation Intel Core i7 processor, Turbo Boost up to 5.0GHz, 16GB 2666MHz DDR4 memory, Radeon Pro 5500 XT with 8GB of GDDR6 memory, 256GB SSD storage, Gigabit Ethernet, Magic Mouse 2, Magic Keyboard - US</textarea>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Update product
                </button>
                <button type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Delete
                </button>
            </div>
        </form>
    </div>
</section>

<script src="/public/js/adminUser.js"></script>
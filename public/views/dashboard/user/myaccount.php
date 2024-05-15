<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["updatePicture"])) {
        if (isset($_FILES["imageUpdate"]) && $_FILES["imageUpdate"]["error"] != 4) {
            updateUserProfile($_SESSION["user"]["id"], $_FILES["imageUpdate"]);
        }
    } elseif (isset($_POST["updateUsernameSubmit"])) {
        if (isset($_POST["updateUsernameField"])) {
            updateUsername($_SESSION["user"]["id"], $_POST["updateUsernameField"]);
        }
    } elseif (isset($_POST["updatePasswordSubmit"])) {
        $oldPass = htmlspecialchars(trim($_POST["passwordOld"]));
        $newPass = htmlspecialchars(trim($_POST["passwordNew"]));
        $newPassConfirm = htmlspecialchars(trim($_POST["passwordNewConfirm"]));
        updateUserPassword($_SESSION["user"]["id"], $oldPass, $newPass, $newPassConfirm);
    } elseif (isset($_POST["updateDescSubmit"])) {
        updateUserBiography($_SESSION["user"]["id"], $_POST["updateDescription"]);
    } elseif (isset($_POST["deleteProfilePicture"])) {
        deleteUserProfile($_SESSION["user"]["id"]);
    } elseif (isset($_POST["deleteProfile"])) {
        deleteUser($_SESSION["user"]["id"], $_POST["password"]);
    }
}
?>

<div class="w-11/12 h-fit shadow-sm rounded-lg bg-white px-2 py-2.5 select-none dark:bg-slate-700">
    <div class="flex flex-col w-full h-full">
        <h2 class="mt-2 ml-1.5 text-2xl text-gray-700 font-bold border-b-2 border-opacity-50 bg-clip-border border-gray-10 dark:text-slate-200">Informations Personnelles </h2>
        <div class="h-full w-8/12 mx-auto align-middle">
            <div class="px-4 py-6 flex flex-col items-center border-separate border-b sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-slate-300">Photo de profil</dt>
                <div class="flex flex-row items-center justify-between mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 dark:text-slate-300">
                    <?php if($_SESSION["user"]["image"] != ""): ?>
                        <img class="rounded-full w-24 h-24 shadow-lg border-2 border-gray-300" src="<?= $_SESSION["user"]["image"] ?>" alt="image de profil">
                    <?php else : ?>
                        <div class="w-24 h-24 bg-gray-500 text-white rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-user text-5xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="flex flex-row gap-x-2.5">
                        <button id="btnUpdateProfile" data-modal-target="deleteProfilePicture" data-modal-toggle="deleteProfilePicture" class="font-medium text-red-600 hover:text-red-500" type="button">Supprimer</button>
                        <span class="border-r border-separate border-spacing-2.5"></span>
                        <button id="btnUpdateProfile" data-modal-target="updateProfilPicture" data-modal-toggle="updateProfilPicture" class="font-medium text-indigo-600 hover:text-indigo-500" type="button">Modifier</button>
                    </div>
                </div>
            </div>
            <div class="px-4 py-6 flex flex-col items-center border-separate border-b sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-slate-300">Username</dt>
                <dd class="flex items-center justify-between mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?= $_SESSION["user"]["username"] ?>
                    <button id="btnUpdateProfile" data-modal-target="updateUsername" data-modal-toggle="updateUsername" class="font-medium text-indigo-600 hover:text-indigo-500" type="button">Modifier</button>
                </dd>
            </div>
            <div class="px-4 py-6 flex flex-col items-center border-separate border-b sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-slate-300">Adresse Mail</dt>
                <dd class="flex items-center justify-between mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?= $_SESSION["user"]["email"] ?>
                    <button id="btnUpdateProfile" data-modal-target="updateProfilPicture" disabled data-modal-toggle="updateProfilPicture" class="font-medium text-indigo-600 hover:text-indigo-500" type="button">Modifier</button>
                </dd>
            </div>
            <div class="px-4 py-6 flex flex-col items-center border-separate border-b sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-slate-300">Mots de passe</dt>
                <dd class="flex items-center justify-between mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    **********
                    <button id="btnUpdateProfile" data-modal-target="updatePassword" data-modal-toggle="updatePassword" class="font-medium text-indigo-600 hover:text-indigo-500" type="button">Modifier</button>
                </dd>
            </div>
            <div class="px-4 py-6 flex flex-col items-center sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-slate-300">Biographie</dt>
                <dd class="flex items-center justify-between mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 dark:text-slate-300">
                    <p class="line-clamp-1"><?= $_SESSION["user"]["biography"] ?></p>
                    <button id="btnUpdateProfile" data-modal-target="updateBiography" data-modal-toggle="updateBiography" class="font-medium text-indigo-600 hover:text-indigo-500" type="button">Modifier</button>
                </dd>
            </div>
            <div class="px-4 py-6 mt-5 flex flex-col items-center sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-slate-300">Voulez-vous supprimer votre compte?</dt>
                <dd class="flex items-center justify-between mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <button id="btnDeleteProfile" data-modal-target="deleteProfile" data-modal-toggle="deleteProfile" class="font-medium text-red-600 hover:text-red-500 hover:font-semibold" type="button">Supprimer</button>
                </dd>
            </div>
        </div>
    </div>
</div>

<div id="updateProfilPicture" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[90] justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full z-auto max-w-2xl h-full md:h-auto">
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Modifier la photo de profil
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="updateProfilPicture">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="flex items-center h-max flex-col justify-center w-full">
                    <div class="w-full mb-2.5">
                        <span class="text-lg font-medium text-gray-600 text-left dark:text-slate-400">Image de profil :</span>
                    </div>
                    <div class="w-full mb-1.5 flex flex-col">
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="imageUpdate" name="imageUpdate" type="file">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG, JPG, JPEG (MAX. 800x400px).</p>
                    </div>
                    <div class="mt-3">
                        <input name="updatePicture" type="submit" value="Mettre à jour" class="py-2 px-3 bg-indigo-600 cursor-pointer text-medium text-white font-medium rounded-lg hover:bg-indigo-500 transition duration-75">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="updateUsername" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[90] justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 z-50 w-full max-w-2xl h-full md:h-auto">
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Modifier votre username
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="updateUsername">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mt-2 mb-1.5">
                    <span class="text-gray-600 text-md font-medium dark:text-slate-300">Nouveau username :</span>
                </div>
                <div class="flex w-full">
                    <label for="website-admin" class="sr-only">Username</label>
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                        </svg>
                    </span>
                    <input type="text" name="updateUsernameField" id="website-admin" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ex: elonmusk">
                </div>
                <div class="mt-3 w-full flex justify-end">
                    <input name="updateUsernameSubmit" type="submit" value="Mettre à jour" class="py-2 px-3 bg-gradient-to-tl to-indigo-600 from-blue-500 cursor-pointer text-medium text-white font-medium rounded-lg hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                </div>
            </form>
        </div>
    </div>
</div>

<div id="deleteProfilePicture" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteProfilePicture">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Voulez-vous vraiment supprimer votre image de profil ?</h3>
                <form action="" method="post" enctype="multipart/form-data">
                    <input name="deleteProfilePicture" type="submit" value="Oui, supprimer" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    <button data-modal-hide="deleteProfilePicture" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Annuler</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="deleteProfile" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteProfile">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Voulez-vous vraiment supprimer votre profil ?</h3>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-6 w-full">
                        <label for="password" class="block mb-2 text-sm text-left font-medium text-gray-900 dark:text-white">Votre mots de passe</label>
                        <input type="password" aria-required="true" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required />
                        <p class="mt-1 text-sm text-justify text-gray-500 dark:text-gray-300" id="file_input_help">Si vous entrez votre mots de passe vous acceptez la suppression.</p>
                    </div>
                    <div class="w-full flex flex-row">
                        <input name="deleteProfile" type="submit" value="Oui, je veux supprimer mon compte!" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        <button data-modal-hide="deleteProfile" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="updatePassword" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Modifier votre mots de passe
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="updatePassword">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="flex items-center h-max flex-col justify-center w-full">
                    <div class="mb-6 w-full">
                        <label for="passwordOld" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ancien mots de passe</label>
                        <input type="password" name="passwordOld" id="passwordOld" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required />
                    </div>
                    <div class="mb-6 w-full">
                        <label for="passwordNew" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nouveau mots de passe</label>
                        <input type="password" name="passwordNew" id="passwordNew" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Minimum 6 caractères et 1 chiffre.</p>
                    </div>
                    <div class="mb-6 w-full">
                        <label for="passwordNewConfirm" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmer le nouveau mots de passe</label>
                        <input type="password" name="passwordNewConfirm" id="passwordNewConfirm" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required />
                    </div>
                    <div class="mt-3 w-full flex justify-end">
                        <input name="updatePasswordSubmit" type="submit" value="Mettre à jour" class="py-2 px-3 bg-gradient-to-tl to-indigo-600 from-blue-500 cursor-pointer text-medium text-white font-medium rounded-lg hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="updateBiography" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Modifier votre biographie
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="updateBiography">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="flex items-center h-max flex-col justify-center w-full">
                    <div class="mb-6 w-full">
                        <label for="passwordOld" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="updateDescription" name="updateDescription" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" maxlength="500" cols="30" rows="10"><?= $_SESSION["user"]["biography"] ?></textarea>
                        <div id="textareaBError" class="counter"><span id="counter">0</span> caractères (500 max)</div>
                    </div>
                    <div class="mt-3 w-full flex justify-end">
                        <input name="updateDescSubmit" type="submit" value="Mettre à jour" class="py-2 px-3 bg-gradient-to-tl to-indigo-600 from-blue-500 cursor-pointer text-medium text-white font-medium rounded-lg hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/public/js/myaccount.js" crossorigin="anonymous"></script>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        document.getElementById('updateProfilPicture').click();
        document.getElementById('updateUsername').click();
        document.getElementById('updatePassword').click();
        document.getElementById('updateBiography').click();
        document.getElementById('deleteProfilePicture').click();
        document.getElementById('deleteProfile').click();
    });
</script>
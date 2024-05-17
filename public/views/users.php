<?php
$users = getUser(-1);
?>

<form method="post" action="">
    <label class="group mx-auto my-3 relative bg-white min-w-sm max-w-2xl flex flex-col md:flex-row items-center justify-center border p-1 rounded-2xl shadow-md" for="searchbar">
        <input id="searchbar" name="searchbar" maxlength="30" placeholder="Rechercher un utilisateur" class="px-6 py-2 w-full rounded-md flex-1 outline-none bg-white">
        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-black border-black text-white fill-white active:scale-95 duration-100 border will-change-transform overflow-hidden relative rounded-xl transition-all disabled:opacity-70">
            <div class="relative">
                <div class="flex items-center transition-all opacity-1 valid:">
                    <span class="text-sm font-semibold whitespace-nowrap truncate mx-auto">
                        Rechercher
                    </span>
                </div>
            </div>
        </button>
    </label>
</form>

<div class="h-screen">
    <div class="w-[90%] h-[90%] rounded-lg shadow-sm bg-white mx-auto px-6 py-4 dark:bg-slate-700">
        <div class="grid grid-rows-subgrid gap-5 lg:gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($users as $user): ?>
                <a href="?page=profil&ref=<?= $user["reference"] ?>" class="py-2 flex items-center justify-center w-full max-w-sm hover:scale-105 duration-75 transition  bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex flex-col items-center">
                        <img class="w-24 h-24 mb-3 object-cover rounded-full shadow-lg" src="<?= $user["image"] ?>" alt="Bonnie image"/>
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white"><?= $user["username"] ?></h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400"><?= getRole($user["roleId"])["name"] ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
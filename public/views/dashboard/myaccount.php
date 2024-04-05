<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "" ) {
        $status = updateUserProfile($_SESSION["user"]["id"], $_FILES["image"]);

        if ($status["type"] == "success") {
            echo "<script>window.location.href = './index.php?page=myaccount&success=1&message=Image modifié avec succès'</script>";
        } else {
            echo "<script>window.location.href = './index.php?page=myaccount&error=1&message=Erreur avec l '</script>";
        }

    }
}

?>

<div class="w-11/12 h-5/6 rounded-lg bg-white px-2 py-2.5">
    <div class="flex flex-col h-full">
        <h2 class="mt-2 ml-1.5 text-2xl text-gray-700 font-bold border-b-2 border-opacity-50 bg-clip-border border-gray-10">Informations Personnelles </h2>
        <div class="h-full">
            <form action="" enctype="multipart/form-data" method="post" class="flex justify-evenly items-end flex-col h-full gap-2 mt-5 mx-2.5">
                <div class="w-[100%]">
                    <div class="mb-4">
                        <label class="block mb-2  text-gray-600 dark:text-white font-semibold" for="image">Photo de profile</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="image" id="image" name="image" type="file">
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="image">Optez pour une photo de profil qui vous distingue des autres.</div>
                    </div>
                    <div class="flex flex-row gap-x-2 justify-between mb-4">
                        <div class="flex flex-col gap-1 w-[48%]">
                            <label for="username" class="text-gray-600 font-semibold">Nom d'utilisateur</label>
                            <input type="text" name="username" id="username" class="border-2 border-gray-300 rounded-md p-1.5" value="<?= $_SESSION["user"]["username"] ?>">
                        </div>
                        <div class="flex flex-col gap-1 w-[48%]">
                            <label for="email" class="text-gray-600 font-semibold">Adresse email</label>
                            <input type="email" name="email" id="email" class="border-2 border-gray-300 rounded-md p-1.5" value="<?= $_SESSION["user"]["email"] ?>">
                        </div>
                    </div>
                    <div class="mb-3 flex flex-row gap-x-2 justify-between">
                        <div class="flex flex-col gap-1 w-[48%]">
                            <label for="password" class="text-gray-600 font-semibold">Ancien mot de passe</label>
                            <input type="password" name="password" id="password" class="border-2 border-gray-300 rounded-md p-1.5">
                        </div>
                        <div class="flex flex-col gap-1 w-[48%]">
                            <label for="new_password" class="text-gray-600 font-semibold">Nouveau mot de passe</label>
                            <input type="password" name="new_password" id="new_password" class="border-2 border-gray-300 rounded-md p-1.5">
                        </div>
                        <div class="flex flex-col gap-1 w-[48%]">
                            <label for="new_password_confirm" class="text-gray-600 font-semibold">Confirmer le nouveau mot de passe</label>
                            <input type="password" aria-autocomplete="none" name="new_password_confirm" id="new_password_confirm" class="border-2 border-gray-300 rounded-md p-1.5">
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label for="bio" class="mb-1.5 text-gray-600 font-semibold">Biographie</label>
                        <textarea name="bio" id="bio" class="border-2 border-gray-300 rounded-md p-1.5" cols="30" maxlength="255" rows="10"><?php if (trim($_SESSION["user"]["biography"]) == ""){echo "";} else {echo trim($_SESSION["user"]["biography"]);} ?></textarea>
                    </div>
                </div>

                <button type="submit" name="update" class="bg-indigo-500 hover:bg-indigo-600 duration-75 transition-all w-fit text-white font-semibold rounded-md p-2.5">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
<?php

// Vérification de la référence
if (!isset($_GET["ref"]) && !getPostByRef($_GET["ref"])) {
    header("Location: index.php?page=myaccount");
}

$post = getPostByRef($_GET["ref"]);
$categories = getCategory(-1);

// Vérification de l'utilisateur
if ($_SESSION["user"]["id"] != $post["createdBy"]) {
    newNotification("error", "Vous n'avez pas le droit de modifier ce post.", true, "fa-exclamation-circle");
    header("Location: index.php?page=myaccount");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updatePost"])) {
    if (!isset($_POST["title"]) && !isset($_POST["description"]) && !isset($_POST["category"])) {
        newNotification("error", "Veuillez completez tous les champs.", true, "fa-exclamation-circle");
        header("Refresh: 0");
    } else {
        $status = updatePostUserByRef($post["reference"], $_POST["title"], $_POST["description"], $_FILES["image"], $_POST["category"], $_SESSION["user"]["id"], $post["createdBy"]);
        if ($status["type"] == "success"){
            newNotification("success", $status["message"], true, "fa-check-circle");
            header("Location: index.php?page=mypost");
        } else {
            newNotification("error", $status["message"], true, "fa-exclamation-circle");
            header("Refresh: 0");
        }
    }
}

?>

<div class="md:w-10/12 w-[98%] h-[85%] shadow bg-white px-3.5 rounded-lg py-2.5 dark:bg-slate-700">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <div class="w-full m-3">
                <h3 class="text-lg font-semibold border-b-2 border-spacing-y-3 w-full border-separate border-gray-200 dark:text-slate-200">Modification du post - <?= $post["reference"] ?></h3>
            </div>
            <form action="" method="post" class="space-y-3" enctype="multipart/form-data">
                <div class="col-span-full">
                    <div class="flex flex-row items-center justify-around">
                        <span class="font-semibold text-gray-800 text-md dark:text-slate-300">Image : </span>
                        <img src="<?= $post["photo"] ?>" class="rounded-lg max-h-[256px] max-w-[256px]" alt="img post">
                    </div>
                    <div>
                        <label for="photo" class="block text-sm font-medium text-white">Photo</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <input class="block w-[90%] mx-auto text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" name="image" accept="image/*" aria-describedby="post_image" id="image" type="file">
                        </div>
                    </div>
                </div>
                <div class="border-b border-separate w-1/2 mx-auto"></div>
                <div class="flex-row flex items-center justify-around">
                    <label for="title" class="block text-gray-800 w-1/4 text-center font-medium dark:text-slate-300">Titre :</label>
                    <input type="text" name="title" id="title" autocomplete="title" class="rounded-lg mx-auto border-gray-300 w-1/2 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50 dark:bg-slate-600 dark:text-slate-300" value="<?= $post["title"] ?>">
                </div>
                <div class="border-b border-separate w-1/2 mx-auto"></div>
                <div class="flex flex-row items-center justify-evenly">
                    <label for="category" class="block text-gray-800 w-1/4 text-center font-medium dark:text-slate-300">Catégories :</label>
                    <select name="category" id="category" class="w-1/2 rounded-lg mx-auto border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50 dark:bg-slate-600 dark:text-slate-300">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category["id"] ?>" <?=
                            $category["id"] == $post["postCategoryId"] ? "selected" : "" ?>>
                                <?= ucfirst($category["name"])?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="border-b border-separate w-1/2 mx-auto"></div>
                <div class="w-full">
                    <label for="description" class="block text-gray-800 font-medium dark:text-slate-300" >Description :</label>
                    <textarea name="description" id="description" rows="15" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50 dark:bg-gray-600 dark:text-slate-200"><?= $post["description"] ?></textarea>
                </div>
                <div>
                    <input type="submit" value="Sauvegarder" name="updatePost" id="updatePost" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">
                </div>
            </form>
        </div>
    </div>
</div>
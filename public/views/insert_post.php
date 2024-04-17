<?php
session_start();
require_once "../../private/functions/functions.php";

$pdo = dbConnect();

$sql = "SELECT * FROM `postCategory`";

$stmt = $pdo->query($sql);

$postCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars($_POST["description"]);
    addPost($_POST["title"], $_POST["description"], $_POST["postCategoryId"], uploadImage($_FILES["image"]), $_SESSION["user"]["id"]);
} 

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/main.css">
    <title>forum - insert post</title>
</head>
<body>
<div class="flex flex-col items-center justify-center min-h-screen bg-[url('/public/image/background/login_bg.jpeg')] bg-fixed bg-no-repeat bg-center bg-blend-darken">
    <div class="w-96 backdrop-blur-2xl p-6 rounded-xl bg-black/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">Post</h1>
        <form enctype="multipart/form-data" action="" method="post" class="mt-8 space-y-6">
            <div class="col-span-full">
                <label for="photo" class="block text-sm font-medium text-white">Photo</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" name="image" aria-describedby="user_avatar_help" id="image" type="file">
                        </div>
                    </div>
        
            <div class="space-y-2">
                <label for="title" class="block text-sm font-medium text-white">Titre</label>
                <input type="text" name="title" id="title" autocomplete="title" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                </div>
            <div class="col-span-full">
                <label for="description" class="block text-sm font-medium text-white">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-400 focus:ring-opacity-50"></textarea>
                </div>
<!-- Button dropdown  -->  
            <label for="postCategoryId" class="block text-sm font-medium text-white">Catégories</label>
            <select id="postCategoryId" name="postCategoryId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>Choisis une catégorie</option>
                <?php foreach ($postCategory as $category) { ?>
                    <option value="<?= $category["id"] ?>">
                        <?= $category["name"] ?>
                    <?php } ?>
                </option>
            </select>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">Poster</button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
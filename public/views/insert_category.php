<?php
session_start();
require_once "../../private/functions/functions.php";

$pdo = dbConnect();

$sql = "SELECT * FROM postCategory";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$addCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newCategory"])) {
    $name = htmlspecialchars($_POST["nameCategory"]);
    addCategory($name, $_SESSION["user"]["id"]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/main.css">
    <title>Forum - insert category</title>
</head>
<body>
<?php if (isset($_GET["success"]) and $_GET["success"] == 1): ?>
    <div id="toast-success"
        class="fixed top-5 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow"
        role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3a1 1 0 102 0v-3zm0 6a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
            <span class="sr-only">icon success</span>
        </div>
        <div class="ms-3 text-sm font-normal"><?= $_GET["message"] ?></div>
        <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8"
                data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Fermer</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7
                    7l-6 6"/>
            </svg>
        </button>
    </div>
<?php endif; ?>

<div class="flex flex-col items-center justify-center min-h-screen bg-[url('/public/image/background/login_bg.jpeg')] bg-fixed bg-no-repeat bg-center bg-blend-darken">
    <div class="w-96 p-6 rounded-xl bg-black/10 shadow-2xl">
        <h1 class="text-4xl font-bold text-center text-white">ajouter une catégorie</h1>
        <form enctype="multipart/form-data" action="" method="post" class="mt-8 space-y-6">
            <div class="col-span-full">
            <div class="space-y-2">
                <label for="" class="form-label">Nom de la catégoire</label>
                <input type="text" class="form-control" id="category" name="nameCategory" placeholder="Entrez le nom de la nouvelle catégorie.">
            <button type="submit" name="newCategory" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-500 transition-all">Envoyer</button>
        </form>
    </div>
</div>

</body>
</html>
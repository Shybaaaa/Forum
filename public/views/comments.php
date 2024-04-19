<?php

require_once "../../private/functions/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = htmlspecialchars($_POST["message"]);
    addComment($_POST["message"], $_SESSION["user"]["id"]);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/css/main.css">

</head>
<body>
<form>
    <!-- <p href="#?id=<?= $comments["id"] ?>" class="text-sm pb-3">
            <a href="#" class="font-semibold hover:text-gray-800"><?= getUser($comments["createdBy"])["username"] ?></a> <?= $comments["createdAt"] ?>
        </p> -->
    <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
        <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
            <label for="comment" class="sr-only">Commentaire</label>
            <textarea id="comment" rows="4"
                    class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                    placeholder="Message" required></textarea>
        </div>
        <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
            <button type="submit"
                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                Envoyer
            </button>
            <div class="flex ps-0 space-x-1 rtl:space-x-reverse sm:ps-2">
                <button type="button"
                        class="inline-flex justify-center items-center p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 12 20">
                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                            d="M1 6v8a5 5 0 1 0 10 0V4.5a3.5 3.5 0 1 0-7 0V13a2 2 0 0 0 4 0V6"/>
                    </svg>
                    <span class="sr-only">Attach file</span>
                </button>
                <button type="button"
                        class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z"/>
                    </svg>
                    <span class="sr-only">Add emoji</span>
                </button>
                <button type="button"
                        class="inline-flex justify-center items-center p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 18">
                        <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                    </svg>
                    <span class="sr-only">Upload image</span>
                </button>
            </div>
        </div>
    </div>
</form>
<?php require_once "../../public/include/footer.php"; ?>
</body>
</html>
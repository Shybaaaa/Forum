<?php
session_start();
require_once "../../../private/functions/functions.php";
if (!isset($_SESSION["user"])) {
    header("Location: /setting.php?error=1");
}

$config = parse_ini_file("../../../config.ini");

$users = getUser($_SESSION["user"]["id"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/main.css">
    <title>Document</title>
</head>
<body>
    <?php require_once "../../../public/include/navbar.php" ?>


    <div class="w-full mb-4 select-none">
        <?php if (isset($_SESSION["user"]) && $_SESSION["user"]): ?>
            <div class="flex items-center gap-3 rounded p-1 w-full ">
                <?php if ($_SESSION["user"]["image"]): ?>
                    <img class="w-10 h-10 rounded-full" src="<?= $_SESSION["user"]["image"] ?>" alt="">
                <?php else: ?>
                    <i class="fa-solid fa-user text-xl rounded-full p-3 bg-gray-500 text-white"></i>
                <?php endif; ?>
                <div class="font-medium dark:text-white">
                    <div><?= $users["username"] ?></div>
                    <div class="text-sm text-gray-500 dark:text-gray-400"><?= $role["name"] ?></div>
                </div>
                    <?php
                    if ($users["isActive"] == 1) {
                    ?>
                    <a class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" href="../../../public/views/dashboard/delete.php?id=<?= $users["id"] ?>">supprimer le compte</a>
                    <?php
                    } else {
                    ?>
                    <a class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" href="../../../public/views/dashboard/delete.php?id=<?= $users["id"] ?>">restaurer le compte</a>
                    <?php
                    }
                    ?>
            </div>
            <?php endif; ?>
    </div>

    <?php require_once "../../../public/include/footer.php" ?>
</body>
</html>
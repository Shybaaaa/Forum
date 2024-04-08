<?php
session_start();
require_once "private/functions/functions.php";
dbConnect();
$config = parse_ini_file("config.ini");

if (isset($_GET["disconnect"]) && $_GET["disconnect"] == 1) {
    disconnect();
}

?>

<!doctype html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $config["APP_NAME"]?></title>
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>

<?php require_once("public/include/dark_mode.php") ?>
<?php require_once("public/include/navbar.php") ?>
<?php require_once("public/views/home.php") ?>
<?php if (isset($_GET["success"]) and $_GET["success"] == 1): ?>
    <div id="toast-success"
         class="fixed top-20 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow"
         role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <i class="fa-solid fa-thumbs-up fill-current" aria-hidden="true"></i>
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
<div class="app">
    <?php
        if (isset($_GET["page"])) {
            switch ($_GET["page"]) {
                case "home":
                    require_once "public/views/home.php";
                    break;
                default:
                    require_once "public/views/home.php";
                    break;
            }
        } else {
            require_once "public/views/home.php";
        }
    ?>
</div>


<?php require_once("public/include/footer.php") ?>
</body>
</html>
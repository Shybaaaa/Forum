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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $config["APP_NAME"]?></title>
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>
<?php require_once("public/include/navbar.php") ?>


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
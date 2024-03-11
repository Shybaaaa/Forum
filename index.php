<?php
session_start();
require_once "private/functions/functions.php";
dbConnect();
$config = parse_ini_file("config.ini");
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>
<?php require_once("public/include/navbar.php") ?>


<div class="app">
        <?php
            switch ($_GET["page"]) {
                default:
                    require_once "public/views/home.php";
                    break;
            }
        ?>
    </div>


    <?php require_once("public/include/footer.php") ?>
</body>
</html>
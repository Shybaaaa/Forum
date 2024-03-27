<?php
session_start();
require_once "../../../private/functions/functions.php";
if (!isset($_SESSION["user"])) {
    header("Location: /index.php?error=1");
}

$config = parse_ini_file("../../../config.ini");

if (isset($_GET["disconnect"]) && $_GET["disconnect"] == 1) {
    disconnect();
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum - Tableau de bord</title>
    <link rel="stylesheet" href="../../css/main.css">
</head>
<body>


</body>
</html>
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
    <script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>
</head>
<body>
<?php require_once("../../include/navbar.php") ?>


<?php require_once("../../include/footer.php") ?>
</body>
</html>
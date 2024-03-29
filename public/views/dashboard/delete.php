<?php
session_start();
require_once "../../../private/functions/functions.php";

print_r($_GET);

$users = getUser($_SESSION["user"]["id"]);

if ($users["isActive"] == 1) {

    safeDelete($users["id"]);

} else  {

    safeRestore($_GET["id"]);

}

// $id = $_GET["id"];

// $pdo = dbConnect();

// $sql = "UPDATE users SET isActive = 0 WHERE id = ?";

// $stmt = $pdo->prepare($sql);

// $stmt->execute([$id]);

// header("Location: ../../../public/views/dashboard/setting.php");
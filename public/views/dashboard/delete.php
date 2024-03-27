<?php

require_once "../../../private/functions/functions.php";

safeDelete($_GET["id"]);

// $id = $_GET["id"];

// $pdo = dbConnect();

// $sql = "UPDATE users SET isActive = 0 WHERE id = ?";

// $stmt = $pdo->prepare($sql);

// $stmt->execute([$id]);

// header("Location: ../../../public/views/dashboard/setting.php");
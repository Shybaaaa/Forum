<?php

function dbConnect()
{
    $config = parse_ini_file(__DIR__ . "/../../config.ini");
    try {
        $pdo = new PDO("mysql:host=$config[DB_HOST];dbname=$config[DB_NAME];charset=utf8", $config['DB_USER'], $config["DB_PASS"]);
    } catch (PDOException $e) {
        header("Location: ./index.php?error=1&message=Erreur de connexion à la base de données");
    }
    return $pdo;
}

function addUser($username, $description, $email, $password, $vpassword)
{
    $username = htmlspecialchars($username);
    $description = htmlspecialchars($description);
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $vPassword = htmlspecialchars($vPassword);

    if ($description != "" );

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $hPassword = md5($password);

        if ($password == $vPassword) {
            $pdo = dbConnect();
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([$username, $email, $hPassword]);

            header("Location: ./index.php?succes=1&message=Votre compte a été créé avec succès");
        } else {
            header("Location: ./index.php?error=1&message=Entrez le même mot de passe");
        }
    } else {
        header("Location: ./index.php?error=2&message=Mauvaise adresse email");
    }
}

function loginUser($email, $password)
{
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $hPassword = md5($password);    // "Cryptage" du mots de passe entré par l'utilisateur

    if (filter_var($email, FILTER_VALIDATE_EMAIL) and filter_var($password, FILTER_SANITIZE_STRING)) {
        $pdo = dbConnect();

        // Requête pour récupérer l'utilisateur
        $sql = "SELECT users.username, users.email, users.image, users.roleId, users.surname from users where users.email = ? AND users.password = ? AND users.isActive = 1 AND users.isDeleted = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $hPassword]);

        $isUser = $stmt->fetch();

        // Si l'utilisateur existe et que le mots de passe est bon, on le connecte.
        if ($isUser) {
            $_SESSION["user"] = [
                "username" => $isUser["username"],
                "email" => $isUser["email"],
                "image" => $isUser["image"],
                "roleId" => $isUser["roleId"],
                "surname" => $isUser["surname"],
            ];
            header("Location: /index.php?success=1&message=Vous êtes connecté avec succès");
        } else {
            header("Location: login.php?error=2");
        }
    } else {
        header("Location: login.php?error=3");
    }
}

function updateUser($id, $username, $surname, $email, $password, $image)
{
    $username = htmlspecialchars($username);
    $surname = htmlspecialchars($surname);
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $image = htmlspecialchars($image);
    $pdo = dbConnect();

    if ($password != "") {
        $hPassword = md5($password);
        $sql = "UPDATE users SET username = ?, surname = ?, email = ?, password = ?, image = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $surname, $email, $hPassword, $image, $id]);
    } else {
        $sql = "UPDATE users SET username = ?, surname = ?, email = ?, image = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $surname, $email, $image, $id]);
    }

    $_SESSION["user"] = [
        "username" => $username,
        "email" => $email,
        "image" => $image,
        "roleId" => $_SESSION["user"]["roleId"],
        "surname" => $surname,
    ];

    header("Location: ./index.php?success=1&message=Votre compte a été modifié avec succès");
}

function addPost($title, $reference, $description){

    $pdo = dbConnect();

    $sql = "INSERT INTO posts () VALUES (?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([]);

    header("Location: ");
  
}
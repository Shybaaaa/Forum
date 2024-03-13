<?php

function dbConnect()
{
    $config = parse_ini_file(__DIR__ . "/../../config.ini");
    try {
        $pdo = new PDO("mysql:host=$config[DB_HOST];port=$config[DB_PORT];dbname=$config[DB_NAME];charset=utf8", $config['DB_USER'], $config["DB_PASS"]);
    } catch (PDOException $e) {
        header("Location: ./index.php?error=1&message=Erreur de connexion à la base de données");
    }
    return $pdo;
}

function addUser($username, $description, $email, $password, $vPassword)
{
//    Déclaration des variables.
    $username = htmlspecialchars($username);
    $description = htmlspecialchars($description);
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $vPassword = htmlspecialchars($vPassword);

//    Vérification de l'entré email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($password === $vPassword) {
            $hPassword = md5($password);
            $pdo = dbConnect();

//            On vérifie si la description est vide ou non pour l'ajouter à la base de données.
            if ($description == ""){
                $sql = "INSERT INTO users (username, email, password, createdAt) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username, $email, $hPassword, date("Y-m-d H:i:s")]);
            } else {
                $sql = "INSERT INTO users (username, biography, email, password, createdAt) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username, $description, $email, $hPassword, date("Y-m-d H:i:s")]);
            }
            header("Location: /index.php?success=1&message=Votre compte a été créé avec succès");
        } else {
            header("Location: /index.php?error=1&message=Entrez le même mot de passe");
        }
    } else {
        header("Location: /index.php?error=2&message=Mauvaise adresse email");
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

function addPost($title, $reference, $description, $postCategoryId){

    $pdo = dbConnect();

    $sql = "INSERT INTO posts (title, description, postCategoryId) VALUES (?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$title, $description, $postCategoryId]);

    header("Location: ");
}

function uploadImage($file, $fileName, $upload){
    $pdo = dbConnect();
    $url = __DIR__ . "/../../public/uploads/";
    $folder = uniqid();
    mkdir($url . $folder);

    $file = $_FILES["image"];
    $fileName = $file["name"];
    $upload = "../../public/uploads/" . $folder . $fileName;
    move_uploaded_file($file["tmp_name"], $upload);
    $sql = "INSERT INTO images (`NAME`) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$fileName]);
}

function disconnect()
{
    session_destroy();
    header("Location: /index.php?success=1&message=Vous êtes déconnecté avec succès");
}

function getPosts($post)
{
    $pdo = dbConnect();

    if ($post === "all") {
        $sql = "SELECT * FROM posts";
    } else {
        $sql = "SELECT * FROM posts WHERE id = ?";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$post]);

    return $stmt->fetchAll();
}
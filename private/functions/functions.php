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

function addUser($username, $description, $email, $password, $vPassword, $image)
{
//    Déclaration des variables.
    $username = htmlspecialchars($username);
    $description = htmlspecialchars($description);
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $vPassword = htmlspecialchars($vPassword);

//    Vérification de l'entré email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//        Vérification des mots de passe
        if ($password === $vPassword) {

//            Vérification de l'unicité du username
            $pdo = dbConnect();
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username]);
            $isUsername = $stmt->fetch();

            if ($isUsername) {
                newLogs("CREATE USER ERROR", "Nom d'utilisateur déjà utilisé : " . $username);
                header("Location: /public/views/register.php?error=3&message=Nom d'utilisateur déjà utilisé");
                exit();
            } else {
                // Vérification de l'unicité de l'email
                $pdo = dbConnect();
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email]);
                $isEmail = $stmt->fetch();

                if ($isEmail) {
                    newLogs("CREATE USER ERROR", "Adresse email déjà utilisée : " . $email);
                    header("Location: /public/views/register.php?error=3&message=Cette adresse email est déjà utilisée");
                    exit();
                } else {
                    $hPassword = md5($password);
                    $pdo = dbConnect();
                    if ($description == "") {
                        if ($image == "") {
                            $sql = "INSERT INTO users (username, email, password, createdAt) VALUES (?, ?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $email, $hPassword, date("Y-m-d H:i:s")]);
                        } else {
                            $urlFile = uploadImage($image);
                            print_r($urlFile);
                            $sql = "INSERT INTO users (username, email, password, createdAt, image) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $email, $hPassword, date("Y-m-d H:i:s"), $urlFile]);
                        }
                    } else {
                        if ($image == "") {
                            $sql = "INSERT INTO users (username, biography, email, password, createdAt) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $description, $email, $hPassword, date("Y-m-d H:i:s")]);
                        } else {
                            $urlFile = uploadImage($image);
                            $sql = "INSERT INTO users (username, biography, email, password, createdAt, image) VALUES (?, ?, ?, ?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $description, $email, $hPassword, date("Y-m-d H:i:s"), $urlFile]);
                        }
                    }
                }
                newLogs("CREATE USER ERROR", "Utilisateur créé avec succès : " . $username . " - " . $email);
                header("Location: /public/views/login.php?success=1&message=Votre compte a été créé avec succès.");
                exit();
            }



        } else {
            newLogs("CREATE USER ERROR", "Mots de passe différents");
            header("Location: /public/views/register.php.php?error=1&message=Entrez le même mot de passe");
            exit();
        }
    } else {
        newLogs("CREATE USER ERROR", "Mauvaise adresse email");
        header("Location: /public/views/register.php?error=2&message=Mauvaise adresse email");
        exit();
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

function addPost($title, $description, $postCategoryId, $photo){

    $pdo = dbConnect();
    $sql = "SELECT * FROM postCategory WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $isCategory = $stmt->fetch();

    if ($isCategory) {
        header("Location: /public/views/insert.php?error=3&message=catégorie non valide");
                exit();
    } else {

        $sql = "INSERT INTO posts (title, description, postCategoryId, photo) VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$title, $description, $postCategoryId, $photo]);

        header("Location: ./index.php");
    }
}

function uploadImage($image){
    if (!$image["error"]) {
        $folderName = uniqid();
        $targetDir = "/public/uploads/";
        mkdir( $_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName);
        $target_file = $_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName . "/" . basename($image["name"]);
        $url_file = $target_file . $folderName . "/" . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                $url = $targetDir . $folderName . "/" . basename($image["name"]);
                newLogs("Image upload", "Image uploadé avec succès : " . $url_file);
                return $url;
            } else {
                newLogs("error", "Erreur upload image (move_uploaded_file)");
                return "Erreur lors de l'upload de l'image";
            }
        } else {
            newLogs("error", "Erreur lors de l'upload de l'image (imageFileType)");
            return "Erreur lors de l'upload de l'image";
        }
    } else {
        newLogs("error", "Erreur lors de l'upload de l'image (error)");
        return "Erreur lors de l'upload de l'image";
    }
}

function disconnect()
{
    session_destroy();
    header("Location: /index.php?success=1&message=Vous êtes déconnecté avec succès");
}

function newLogs($type, $logs)
{
    $ip = $_SERVER["REMOTE_ADDR"];
    $type = htmlspecialchars($type);
    $logs = htmlspecialchars($logs);
    $created = date("Y-m-d H:i:s");
    $pdo = dbConnect();

    $sql = "INSERT INTO logs (type, logs, ip, createdAt) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$type, $logs, $ip, $created]);
}

function getPosts($post)
{
    $pdo = dbConnect();

    if ($post === "all") {
        $sql = "SELECT * FROM posts where isActive = 1 and isDeleted = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "SELECT * FROM posts WHERE id = ? and isActive = 1 and isDeleted = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$post]);
    }

    return $stmt->fetchAll();
}
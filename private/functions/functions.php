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

    //    Fait une vérification pour le mots de passe avec minimum 6 caractères et 1 chiffres
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{ 6,}$/", $password)) {
        newLogs("CREATE USER ERROR", "Mots de passe incorrect");
        header("Location: /public/views/register.php?error=4&message=Le mot de passe doit contenir au moins 6 caractères et 1 chiffre");
        exit();
    }

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
            header("Location: /public/views/register.php?error=1&message=Entrez le même mot de passe");
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
        $sql = "SELECT users.id, users.username, users.email, users.image, users.roleId, users.surname, users.biography from users where users.email = ? AND users.password = ? AND users.isActive = 1 AND users.isDeleted = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $hPassword]);

        $isUser = $stmt->fetch();

        // Si l'utilisateur existe et que le mots de passe est bon, on le connecte.
        if ($isUser) {
            $_SESSION["user"] = [
                "id" => $isUser["id"],
                "username" => $isUser["username"],
                "email" => $isUser["email"],
                "image" => $isUser["image"],
                "roleId" => $isUser["roleId"],
                "surname" => $isUser["surname"],
                "biography" => $isUser["biography"],
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

function updateUserPassword($id, $password, $newPassword, $newPasswordConfirm)
{
    $password = htmlspecialchars($password);
    $newPassword = htmlspecialchars($newPassword);
    $newPasswordConfirm = htmlspecialchars($newPasswordConfirm);
    $pdo = dbConnect();

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user["password"] == md5($password)) {
        if ($newPassword == $newPasswordConfirm) {
            $hPassword = md5($newPassword);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hPassword, $id]);

            header("Location: ./index.php?success=1&message=Votre mot de passe a été modifié avec succès");
        } else {
            header("Location: ./index.php?error=1&message=Les mots de passe ne correspondent pas");
        }
    } else {
        header("Location: ./index.php?error=2&message=Mot de passe incorrect");
    }
}

function updateUserProfile($id, $image){
    $pdo = dbConnect();
    $sql = "SELECT users.image FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user["image"] != ""){
        unlink($_SERVER["DOCUMENT_ROOT"] . $user["image"]);
    }
}


function addPost($title, $description, $postCategoryId, $photo){
    $pdo = dbConnect();
    $sql = "SELECT * FROM postCategory WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $isCategory = $stmt->fetch();
    $reference = uniqid("post_", "true");

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
        $folderName = uniqid("img_", "true");
        $targetDir = "/public/uploads/";
        mkdir( $_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName);
        $target_file = $_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName . "/" . basename($image["name"]);
        $url_file = $target_file . $folderName . "/" . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $name = uniqid();


        if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                $url = $targetDir . $folderName . "/" . basename($image["name"]);
                $url = rename($_SERVER["DOCUMENT_ROOT"] . $url, $_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName . "/" . $name . "." . $imageFileType);
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

function safeDelete($id){
    $pdo = dbConnect();
    $sql = "UPDATE users SET isActive = 0 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: /public/views/dashboard/setting.php");
}

function getUser($id)
{
    $pdo = DBConnect();

    if ($id === -1) {
        $sql = "SELECT * FROM users";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tempUser = $stmt->fetchAll();
    } else {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $tempUser = $stmt->fetch();
    }

    return $tempUser;
}

function getRole($id)
{
    $pdo = dbConnect();
    $sql = "SELECT * FROM roles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function safeRestore($id){
    $pdo = dbConnect();
    $sql = "UPDATE users SET isActive = 1 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: /public/views/dashboard/setting.php");
}

function addCategory($name){
    $name = htmlspecialchars(trim($name));

    $pdo = dbConnect();
    
    $sql = "SELECT * FROM postCategory where name = :name";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":name" => $name
    ]);


    $category = $stmt->fetch();

    if ($category) {
        header("Location: /public/views/insert_category.php?error=1&message=Catégorie déjà existante");
    } else {
        $stmt = $pdo->prepare("INSERT INTO postCategory (name) VALUES (?)");

        $stmt->execute([$name]);
        
        header("Location: /public/views/insert_category.php?success=1&message=Catégorie ajoutée avec succès");
    
    }
}
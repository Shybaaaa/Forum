<?php

function dbConnect()
{
    date_default_timezone_set('Europe/Paris');
    $config = parse_ini_file(__DIR__ . "/../../config.ini");
    try {
        $pdo = new PDO("mysql:host=$config[DB_HOST];port=$config[DB_PORT];dbname=$config[DB_NAME];charset=utf8", $config['DB_USER'], $config["DB_PASS"]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        header("Location: ./index.php?error=1&message=Erreur de connexion à la base de données");
    }
    return $pdo;
}

function addUser($username, $description, $email, $password, $vPassword, $image)
{
    //    Déclaration des variables.
    $username = trim(htmlspecialchars($username));
    $description = trim(htmlspecialchars($description));
    $email = trim(htmlspecialchars($email));
    $password = trim(htmlspecialchars($password));
    $vPassword = trim(htmlspecialchars($vPassword));

    //    Fait une vérification pour le mots de passe avec minimum 6 caractères et minimum 1 chiffres et des caractères spéciaux, et peut etre écrit dans n'importe quel ordr
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$#!%*?&.]{6,}$/", $password)) {
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

            $lastRef = $pdo->query("SELECT id FROM users ORDER BY id desc limit 1")->fetchColumn();
            if ($lastRef === null) {
                $lastRef = 0;
            }

            $reference = "USE_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);


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
                            $sql = "INSERT INTO users (username, email, password, createdAt, reference) VALUES (?, ?, ?, ?,?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $email, $hPassword, date("Y-m-d H:i:s"), $reference]);
                        } else {
                            $urlFile = uploadImage($image);
                            print_r($urlFile);
                            $sql = "INSERT INTO users (username, email, password, createdAt, image, reference) VALUES (?, ?, ?, ?, ?,?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $email, $hPassword, date("Y-m-d H:i:s"), $urlFile, $reference]);
                        }
                    } else {
                        if ($image == "") {
                            $sql = "INSERT INTO users (username, biography, email, password, createdAt, reference) VALUES (?, ?, ?, ?, ?,?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $description, $email, $hPassword, date("Y-m-d H:i:s"), $reference]);
                        } else {
                            $urlFile = uploadImage($image);
                            $sql = "INSERT INTO users (username, biography, email, password, createdAt, image, reference) VALUES (?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$username, $description, $email, $hPassword, date("Y-m-d H:i:s"), $urlFile, $reference]);
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

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pdo = dbConnect();

        // Requête pour récupérer l'utilisateur
        $sql = "SELECT users.id, users.reference, users.username, users.email, users.image, users.roleId, users.biography from users where users.email = ? AND users.password = ? AND users.isActive = 1 AND users.isDeleted = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $hPassword]);

        $isUser = $stmt->fetch();

        // Si l'utilisateur existe et que le mots de passe est bon, on le connecte.
        if ($isUser) {
            $_SESSION["user"] = [
                "id" => $isUser["id"],
                "reference" => $isUser["reference"],
                "username" => $isUser["username"],
                "email" => $isUser["email"],
                "image" => $isUser["image"],
                "roleId" => $isUser["roleId"],
                "surname" => $isUser["surname"],
                "biography" => $isUser["biography"],
            ];
            header("Location: /index.php?success=1&message=Vous êtes connecté avec succès");
        } else {
            $sql = "SELECT users.id FROM users WHERE email = ? and isActive = 0 and isDeleted = 0 and isBanned = 0 and password = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $hPassword]);
            $isUser = $stmt->fetch();

            if ($isUser) {
                return [
                    "id" => $isUser["id"],
                    "isActive" => 0,
                ];
            } else {
                header("Location: login.php?error=2");
            }
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

function updateUserBiography($id, $biography)
{
    $biography = trim(htmlspecialchars($biography));
    if ($biography == "") {
        newLogs("Biography update", "Biographie vide");
        return ["type" => "error", "message" => "Biographie vide"];
    } else {
        if (strlen($biography) > 500) {
            newLogs("Biography update", "Biographie trop longue");
            return ["type" => "error", "message" => "Biographie trop longue"];
        } else {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("UPDATE users SET biography = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
            $stmt->execute([$biography, date("Y-m-d H:i:s"), $id, $id]);
            $_SESSION["user"]["biography"] = $biography;
            newLogs("Biography update", "Biographie modifiée avec succès : " . $biography);
            return ["type" => "success", "message" => "Biographie modifiée avec succès"];
        }
    }
}

function deleteUserProfile($id)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT users.image FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $userPP = $stmt->fetch();

    if ($userPP) {
        $folder = explode("/", $userPP["image"]);
        $folder = array_slice($folder, 0, count($folder) - 1);
        $folder = implode("/", $folder);

        if (file_exists($_SERVER["DOCUMENT_ROOT"] . $userPP["image"])) {
            unlink($_SERVER["DOCUMENT_ROOT"] . $userPP["image"]);
            rmdir($_SERVER["DOCUMENT_ROOT"] . $folder);
        }

        $stmt = $pdo->prepare("UPDATE users SET image = '', updatedAt = ?, updatedBy = ? WHERE id = ?");
        $stmt->execute([date("Y-m-d H:i:s"), $id, $id]);
        $_SESSION["user"]["image"] = "";

        return ["type" => "success", "message" => "Image supprimée avec succès"];
    } else {
        return ["type" => "error", "message" => "Erreur lors de la suppression de l'image"];
    }
}

function updateUserProfile($id, $image)
{
    if (!$image["error"]) {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT users.image FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if ($user["image"] != "") {
            $folder = explode("/", $user["image"]);
            $folder = array_slice($folder, 0, count($folder) - 1);
            $folder = implode("/", $folder);

            if (file_exists($_SERVER["DOCUMENT_ROOT"] . $user["image"])) {
                unlink($_SERVER["DOCUMENT_ROOT"] . $user["image"]);
                rmdir($_SERVER["DOCUMENT_ROOT"] . $folder);
            }

            $newUrl = uploadImage($image);
            $stmt = $pdo->prepare("UPDATE users SET image = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
            $stmt->execute([$newUrl, date("Y-m-d H:i:s"), $id, $id]);

            $_SESSION["user"]["image"] = $newUrl;
            newLogs("Image update", "Image modifiée avec succès : " . $newUrl);
        } else {
            $urlFile = uploadImage($image);
            $stmt = $pdo->prepare("UPDATE users SET image = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
            $stmt->execute([$urlFile, date("Y-m-d H:i:s"), $id, $id]);
            $_SESSION["user"]["image"] = $urlFile;
            newLogs("Image update", "Image modifiée avec succès : " . $urlFile);
        }
        return ["type" => "success", "message" => "Image modifiée avec succès"];
        exit;
    } else {
        return ["type" => "error", "message" => "Erreur lors de l'upload de l'image"];
        exit;
    }
}

function updateUserPassword($id, $oldPass, $newPass, $confirmNewPass)
{
    $oldPass = htmlspecialchars(trim($oldPass));
    $newPass = htmlspecialchars(trim($newPass));
    $confirmNewPass = htmlspecialchars(trim($confirmNewPass));
    $pdo = dbConnect();

    $stmt = $pdo->prepare("SELECT users.password FROM users WHERE id = ? and password = ? and isActive = 1 and isDeleted = 0");
    $stmt->execute([$id, md5($oldPass)]);
    $user = $stmt->fetch();

    if ($user) {
        if ($newPass == $confirmNewPass) {
            if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$#!%*?&.]{6,}$/", $newPass)) {
                newLogs("CREATE USER ERROR", "Mots de passe incorrect");
                return ["type" => "error", "message" => "Le mots de passe doivent contenir au moins 6 caractères et 1 chiffre"];
                exit();
            } else {
                $stmt = $pdo->prepare("UPDATE users SET password = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
                $stmt->execute([md5($newPass), date("Y-m-d H:i:s"), $id, $id]);
                newLogs("Password update", "Mot de passe modifié avec succès");
                return ["type" => "success", "message" => "Mot de passe modifié avec succès"];
                exit;
            }
        } else {
            newLogs("Password update", "Les mots de passe ne correspondent pas");
            return ["type" => "error", "message" => "Les mots de passe ne correspondent pas"];
            exit;
        }
    } else {
        newLogs("Password update", "Mot de passe incorrect");
        return ["type" => "error", "message" => "Mot de passe incorrect"];
        exit;
    }
}

function updateUsername($id, $username)
{
    $username = trim(htmlspecialchars($username));
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $isUsername = $stmt->fetch();

    if ($isUsername) {
        newLogs("Username update", "Nom d'utilisateur déjà utilisé");
        return ["type" => "error", "message" => "Nom d'utilisateur déjà utilisé"];
        exit;
    } else {
        newLogs("Username update", "Nom d'utilisateur modifié avec succès");
        $stmt = $pdo->prepare("UPDATE users SET username = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
        $stmt->execute([$username, date("Y-m-d H:i:s"), $id, $id]);
        $_SESSION["user"]["username"] = $username;
        return ["type" => "success", "message" => "Nom d'utilisateur modifié avec succès"];
        exit;
    }
}


function addPost($title, $description, $postCategoryId, $photo, $id)
{

    $pdo = dbConnect();
    $sql = "SELECT * FROM postCategory WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$postCategoryId]);
    $isCategory = $stmt->fetch();
    $reference = uniqid("post_", "true");

    $lastRef = $pdo->query("SELECT id FROM posts ORDER BY id desc limit 1")->fetchColumn();
    if ($lastRef === null) {
        $lastRef = 0;
    }

    $reference = "POS_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);

    if (!$isCategory) {
        header("Location: /index.php?error=3&message=catégorie non valide");
        exit();
    } else {

        $sql = "INSERT INTO posts (title, description, postCategoryId, photo, reference, createdAt, createdBy) VALUES (?, ?, ?, ?, ?, ?,?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$title, $description, $postCategoryId, $photo, $reference, date("Y-m-d H:i:s"), $id]);

        header("Location: ./index.php");
    }
}

function deleteUser($id, $password)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND password = ?");
    $stmt->execute([$id, md5($password)]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET isDeleted = 1, isActive = 0, updatedAt = ?, updatedBy = ? WHERE id = ?");
        $stmt->execute([date("Y-m-d H:i:s"), $id, $id]);
        newLogs("DELETE USER", "Utilisateur supprimé : " . $id);
        return ["type" => "success", "message" => "Utilisateur supprimé avec succès"];
        exit;
    } else {
        newLogs("DELETE USER", "Mot de passe incorrect");
        return ["type" => "error", "message" => "Mot de passe incorrect"];
        exit;
    }
}

function uploadImage($image)
{
    if (!$image["error"]) {
        $folderName = uniqid("img_", "true");
        $targetDir = "/public/uploads/";
        mkdir($_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName);
        $target_file = $_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName . "/" . basename($image["name"]);
        $url = $target_file . $folderName . "/" . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "webp" || $imageFileType == "gif") {
            if ($image["size"] < 5000000) {
                if (move_uploaded_file($image["tmp_name"], $target_file)) {
                    $name = uniqid();
                    $url = $targetDir . $folderName . "/" . basename($image["name"]);

                    if (rename($_SERVER["DOCUMENT_ROOT"] . $url, $_SERVER["DOCUMENT_ROOT"] . $targetDir . $folderName . "/" . $name . "." . $imageFileType)) {
                        $url = $targetDir . $folderName . "/" . $name . "." . $imageFileType;
                    }

                    newLogs("Image upload", "Image uploadé avec succès : " . $url);
                    return $url;
                } else {
                    newLogs("error", "Erreur lors de l'upload de l'image (move_uploaded_file)");
                    return "";
                }
            } else {
                newLogs("error", "Erreur lors de l'upload de l'image (imageSize)");
                return "";
            }
        } else {
            newLogs("error", "Erreur lors de l'upload de l'image (imageFileType)");
            return "";
        }
    } else {
        newLogs("error", "Erreur lors de l'upload de l'image (error)");
        return "";
    }
}

function disconnect()
{
    session_destroy();
    newLogs("DISCONNECT", "Utilisateur déconnecté : " . $_SESSION["user"]["username"] . " - " . $_SESSION["user"]["email"]);
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

function getPostUser($idUser, $idPost, $isDeleted)
{
    $pdo = dbConnect();

    if (isset($idUser) && $idUser >= 0) {
        if ($isDeleted) {
            $sql = "SELECT * FROM posts WHERE createdBy = ?";
            $sql = ($idPost == "all") ? $sql : $sql . " and id = ?";
            $stmt = $pdo->prepare($sql);

            if ($idPost != "all") {
                $var = [
                    $idUser,
                    $idPost
                ];
            } else {
                $var = [
                    $idUser,
                ];
            }
        } elseif (!$isDeleted) {
            $sql = "SELECT * FROM posts WHERE createdBy = ? and isDeleted = ?";
            $sql = ($idPost == "all") ? $sql : $sql . " and id = ?";
            $stmt = $pdo->prepare($sql);

            if ($idPost != "all") {
                $var = [
                    $idUser,
                    ($isDeleted) ? 1 : 0,
                    $idPost
                ];
            } else {
                $var = [
                    $idUser,
                    ($isDeleted) ? 1 : 0,
                ];
            }
        }

        $stmt->execute($var);
        return $stmt->fetchAll();
    } else {
        return "Erreur";
    }
}

function safeDelete($id)
{
    $pdo = dbConnect();
    $sql = "UPDATE users SET isActive = 0 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    newLogs("DELETE USER", "Utilisateur supprimé : " . $id);

    header("Location: /public/views/dashboard/setting.php");
}

function safeRestore($id)
{
    $pdo = dbConnect();
    $sql = "UPDATE users SET isActive = 1 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    newLogs("RESTORE USER", "Utilisateur restauré : " . $id);

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

    if ($id === -1) {
        $sql = "SELECT * FROM roles";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tempRole = $stmt->fetchAll();
    } else {
        $sql = "SELECT * FROM roles WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $tempRole = $stmt->fetch();
    }

    return $tempRole;
}


function addCategory($name, $id)
{
    $name = htmlspecialchars(trim($name));

    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM postCategory where name = :name");
    $stmt->execute([
        ":name" => $name
    ]);

    $category = $stmt->fetch();

    if ($category) {
        newLogs("CREATE CATEGORY", "Catégorie déjà existante : " . $name);
        header("Location: /public/views/insert_category.php?error=1&message=Catégorie déjà existante");
    } else {
        newLogs("CREATE CATEGORY", "Catégorie ajoutée : " . $name);

        $lastRef = $pdo->query("SELECT id FROM postCategory ORDER BY id desc limit 1")->fetchColumn();
        if ($lastRef === null) {
            $lastRef = 0;
        }

        $reference = "CAT_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);

        $stmt = $pdo->prepare("INSERT INTO postCategory (name, reference, createdAt, createdBy) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $reference, date("Y-m-d H:i:s"), $id]);

        header("Location: /public/views/insert_category.php?success=1&message=Catégorie ajoutée avec succès");
    }
}

function getCategory($id)
{
    $pdo = dbConnect();

    if ($id === -1) {
        $sql = "SELECT * FROM postCategory";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tempCategory = $stmt->fetchAll();
    } else {
        $sql = "SELECT * FROM postCategory WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $tempCategory = $stmt->fetch();
    }

    return $tempCategory;
}

function getNbComments($id)
{
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) as nbComments FROM comments WHERE postId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}


function loginRestore($id)
{
    $pdo = dbConnect();
    $sql = "UPDATE users SET isActive = 1 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($id);

    newLogs("RESTORE USER", "Utilisateur restauré : " . $id);
    header("Location: /index.php?success=1&message=Vous êtes connecté avec succès bon retour parmis nous");
}



function addComment($message, $postId, $reference, $id)
{
    $message = trim(htmlspecialchars($message));

    $pdo = dbConnect();

    $lastRef = $pdo->query("SELECT id FROM comments ORDER BY id desc limit 1")->fetchColumn();
    if ($lastRef === null) {
        $lastRef = 0;
    }
    $reference = "COM_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);

    $sql = "INSERT INTO comments (message, postId , reference, createdAt, createdBy) values (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$message, $postId, $reference, date("Y-m-d H:i:s"), $id]);

    return ["type" => "success", "message" => "Le commentaire a bien été publié."];
}

function addRespondComment($message, $reference)
{
    $pdo = dbConnect();

    $lastRef = $pdo->query("SELECT id FROM comments ORDER BY id desc limit 1")->fetchColumn();
    if ($lastRef === null) {
        $lastRef = 0;
    }

    $reference = "COM_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);


    $sql = "INSERT INTO comments (message, reference) values (?, ?)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$message, $reference]);
}

function getNbPosts($id)
{
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) as nbPosts FROM posts WHERE createdBy = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

function getNbUsers($id)
{
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) as nbUsers FROM users WHERE roleId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

function getPostsWhereCat($catId, $nbPosts, $order)
{
    $pdo = dbConnect();

    if ($nbPosts == -1) {
        $sql = "SELECT * FROM posts WHERE postCategoryId = ? and isActive = 1 and isDeleted = 0 ORDER BY createdAt " . $order;
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$catId]);
    } else {
        $sql = "SELECT * FROM posts WHERE postCategoryId = ? and isActive = 1 and isDeleted = 0 ORDER BY createdAt " . $order . " LIMIT " . $nbPosts;
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$catId]);
    }
    return $stmt->fetchAll();
}

function getNbCommentsForUser($id)
{
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) as nbComments FROM comments WHERE createdBy = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

function getCategoryByRef($ref)
{
    $pdo = dbConnect();
    $sql = "SELECT * FROM postCategory WHERE reference = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ref]);
    $category = $stmt->fetch();

    if ($category) {
        return $category;
    } else {
        return false;
    }
}

function getUserByRef($refUser)
{
    $pdo = dbConnect();
    $sql = "SELECT users.id, users.username, users.email, users.image, users.biography, users.status, users.createdAt, users.isActive, users.isDeleted, users.isBanned, users.roleId FROM users WHERE reference = ? and isActive = 1 and isDeleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$refUser]);
    $user = $stmt->fetch();
    if ($user) {
        return $user;
    } else {
        return false;
    }
}


function getPostByRef($ref)
{
    $pdo = dbConnect();
    $sql = "SELECT * FROM posts WHERE reference = ? and isActive = 1 and isDeleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ref]);
    $post = $stmt->fetch();

    if ($post) {
        return $post;
    } else {
        return false;
    }
}

function getPostsByUser($idUser, $nbPosts, $order)
{
    $pdo = dbConnect();

    if ($nbPosts == -1) {
        $sql = "SELECT * FROM posts WHERE createdBy = ? and isActive = 1 and isDeleted = 0 ORDER BY createdAt " . $order;
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idUser]);
    } else {
        $sql = "SELECT * FROM posts WHERE createdBy = ? and isActive = 1 and isDeleted = 0 ORDER BY createdAt " . $order . " LIMIT " . $nbPosts;
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idUser]);
    }
    return $stmt->fetchAll();
}

function updatePostUserByRef(string $ref, string $title, string $description, $photo, int $postCategoryId, int $userRequest, int $idCreator)
{
    $title = htmlspecialchars(trim($title));
    $description = htmlspecialchars(trim($description));

    $pdo = dbConnect();
    $sql = "SELECT * FROM posts WHERE reference = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ref]);
    $post = $stmt->fetch();

    if ($userRequest === $idCreator) {
        $stmt = $pdo->prepare("SELECT * FROM postCategory WHERE id = ?");
        $stmt->execute([$postCategoryId]);
        $isCategory = $stmt->fetch();

        if ($isCategory) {
            if ($post) {

                $sql = "UPDATE posts SET posts.title = ?, posts.description = ?, posts.postCategoryId = ?, posts.updatedAt = ?, posts.updatedBy = ?";

                // Update de l'image, avec suppression de l'ancienne image.
                if ($photo["error"] == 0) {
                    if ($post["photo"] != "") {
                        $folder = explode("/", $post["photo"]);
                        $folder = array_slice($folder, 0, count($folder) - 1);
                        $folder = implode("/", $folder);
                        unlink($_SERVER["DOCUMENT_ROOT"] . $post["photo"]);
                        rmdir($_SERVER["DOCUMENT_ROOT"] . $folder);
                    }
                    $urlPhoto = uploadImage($photo);
                    $sql = $sql . ", posts.photo = ? ";
                    newLogs("UPDATE POST", "Image modifiée avec succès : " . $urlPhoto);
                }

                $stmt = $pdo->prepare($sql . "WHERE posts.reference = ?");
                if ($photo["error"] == 0) {
                    $stmt->execute([$title, $description, $postCategoryId, date("Y-m-d H:i:s"), $userRequest, $urlPhoto, $ref]);
                } else {
                    $stmt->execute([$title, $description, $postCategoryId, date("Y-m-d H:i:s"), $userRequest, $ref]);
                }

                newLogs("UPDATE POST", "Post modifié avec succès : " . $ref . " (requete par " . $userRequest . ")");
                return ["type" => "success", "message" => "Post modifié avec succès."];
            } else {
                newLogs("ERROR UPDATE POST", "Post innexistant - " . $ref . " (requete par " . $userRequest . ")");
                return ["type" => "error", "message" => "Post innexistant"];
            }
        } else {
            newLogs("ERROR UPDATE POST", "Catégorie non valide - " . $postCategoryId . " (requete par " . $userRequest . ")");
            return ["type" => "error", "message" => "Catégorie non valide"];
        }
    } else {
        newLogs("ERROR UPDATE POST", "Utilisateur non autorisé - " . $userRequest . " (requete par " . $userRequest . ")");
        return ["type" => "error", "message" => "Vous ne pouvez pas modifier ce post."];
    }
}

function searchPost($search)
{

    $pdo = dbConnect();
    if (isset($search) && !empty($search)) {
        $sql = "SELECT * FROM posts
                WHERE posts.title LIKE '%$search%'";

        $stmt = $pdo->query($sql);

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header("Location: ./mypost.php");
    }
}



function getCommentsWherePOS($id)
{
    $pdo = dbConnect();

    $sql = "SELECT * FROM comments WHERE postId = ? and isActive = 1 and isDeleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

<?php
require __DIR__ . "/Notification.php";

function dbConnect(): PDO
{
    $pdo = null;
    date_default_timezone_set('Europe/Paris');
    $config = parse_ini_file(__DIR__ . "/../../config.ini");
    try {
        $pdo = new PDO("mysql:host=$config[DB_HOST];port=$config[DB_PORT];dbname=$config[DB_NAME];charset=utf8", $config['DB_USER'], $config["DB_PASS"]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        header("Location: ./index.php?error=1&message=Erreur de connexion à la base de données");
    }
    return $pdo;
}

function addUser($username, $description, $email, $password, $vPassword, $image): void
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
        newNotification("error", "Le mots de passe doivent contenir au moins 6 caractères et 1 chiffre", true, "fa-circle-exclamation");
        header("Location: /public/views/register.php");
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
                newNotification("error", "Nom d'utilisateur déjà utilisé", true, "fa-circle-exclamation");
                header("Location: /public/views/register.php");
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
                    newNotification("error", "Adresse email déjà utilisée", true, "fa-circle-exclamation");
                    header("Location: /public/views/register.php");
                    exit();
                } else {
                    if (strlen($username) > 1 && strlen($username) < 16){
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
                    } else {
                        newLogs("CREATE USER ERROR", "Nom d'utilisateur trop long ou trop court");
                        newNotification("error", "Nom d'utilisateur trop long ou trop court", true, "fa-circle-exclamation");
                        header("Location: /public/views/register.php");
                        exit();
                    }
                }
                newLogs("CREATE USER", "Utilisateur créé avec succès : " . $username . " - " . $email);
                newNotification("success", "Utilisateur créé avec succès", true, "fa-circle-check");
                header("Location: /public/views/login.php");
                exit();
            }
        } else {
            newLogs("CREATE USER ERROR", "Mots de passe différents");
            newNotification("error", "Les mots de passe ne correspondent pas", true, "fa-circle-exclamation");
            header("Location: /public/views/register.php");
            exit();
        }
    } else {
        newLogs("CREATE USER ERROR", "Mauvaise adresse email");
        newNotification("error", "Veuillez entrer une adresse email correct.", true, "fa-circle-exclamation");
        header("Location: /public/views/register.php");
        exit();
    }
}

function loginUser($email, $password)
{
    $email = htmlspecialchars(trim($email));
    $password = htmlspecialchars(trim($password));
    $hPassword = md5($password);    // "Cryptage" du mots de passe entré par l'utilisateur

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pdo = dbConnect();

        // Requête pour récupérer l'utilisateur
        $sql = "SELECT users.id, users.reference, users.username, users.email, users.image, users.roleId, users.biography from users where users.email = ? AND users.password = ? AND users.isActive = 1 AND users.isDeleted = 0 AND users.isBanned = 0";
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
                "biography" => $isUser["biography"],
            ];
            newNotification("success", "Vous êtes connecté avec succès.", true, "fa-circle-check");
            header("Location: /index.php?page=home");
        } else {
            $sql = "SELECT users.id FROM users WHERE email = ? and isActive = 0 and isDeleted = 1 and isBanned = 0 and password = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $hPassword]);
            $isUser = $stmt->fetch();

            if ($isUser) {
                $var = [
                    "id" => $isUser["id"],
                    "isActive" => 0,
                ];
                setcookie("status", json_encode($var), time() + 3600, "/public/views/login.php");
            } else {
                newNotification("error", "Adresse email ou mots de passe incorrect", true, "fa-circle-exclamation");
            }
        }
    } else {
        newNotification("error", "Adresse email incorrect", true, "fa-circle-exclamation");
    }
    header('Refresh: 0');
}

function updateUserBiography($id, $biography): void
{
    $biography = htmlspecialchars(trim($biography));

    if (strlen($biography) > 55) {
        newLogs("Biography update", "Biographie trop longue");
        newNotification("error", "Biographie trop longue", true, "fa-circle-exclamation");
    } else {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("UPDATE users SET biography = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
        $stmt->execute([$biography, date("Y-m-d H:i:s"), $id, $id]);
        $_SESSION["user"]["biography"] = $biography;
        newLogs("Biography update", "Biographie modifiée avec succès : " . $biography);
        newNotification("success", "Biographie modifiée avec succès", true, "fa-circle-check");
    }
    header("Refresh: 0");
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

        newLogs("Image delete", "Image supprimée avec succès");
        newNotification("success", "Image supprimée avec succès", true, "fa-circle-check");
    } else {
        newLogs("Image delete", "Erreur lors de la suppression de l'image");
        newNotification("error", "Erreur lors de la suppression de l'image", true, "fa-circle-exclamation");
    }
    header("Refresh: 0");
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
        } else {
            $urlFile = uploadImage($image);
            $stmt = $pdo->prepare("UPDATE users SET image = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
            $stmt->execute([$urlFile, date("Y-m-d H:i:s"), $id, $id]);
            $_SESSION["user"]["image"] = $urlFile;
        }
        newLogs("Image update", "Image modifiée avec succès : " . $urlFile);
        newNotification("success", "Image modifiée avec succès", true, "fa-circle-check");
    } else {
        newLogs("Image update", "Erreur lors de l'upload de l'image");
        newNotification("error", "Erreur lors de l'upload de l'image", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
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
                newNotification("error", "Le mots de passe doivent contenir au moins 6 caractères et 1 chiffre", true, "fa-circle-exclamation");
            } else {
                $stmt = $pdo->prepare("UPDATE users SET password = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
                $stmt->execute([md5($newPass), date("Y-m-d H:i:s"), $id, $id]);
                newLogs("Password update", "Mot de passe modifié avec succès");
                newNotification("success", "Mot de passe modifié avec succès", true, "fa-circle-check");
            }
        } else {
            newLogs("Password update", "Les mots de passe ne correspondent pas");
            newNotification("error", "Les mots de passe ne correspondent pas", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("Password update", "Mot de passe incorrect");
        newNotification("error", "Mot de passe incorrect", true, "fa-circle-exclamation");
    }
    header("Refresh: 0");
}

function updateUserEmail(int $id, string $oldMail, string $newMail, string $confirmNewMail)
{
    $oldMail = filter_var(trim($oldMail, FILTER_VALIDATE_EMAIL));
    $newMail = filter_var(trim($newMail, FILTER_VALIDATE_EMAIL));
    $confirmNewMail = filter_var(trim($confirmNewMail, FILTER_VALIDATE_EMAIL));
    $pdo = dbConnect();

    $stmt = $pdo->prepare("SELECT users.email FROM users WHERE id = ? and email = ? and isActive = 1 and isDeleted = 0");
    $stmt->execute([$id, $oldMail]);
    $user = $stmt->fetch();

    if ($user) {
        if ($newMail == $confirmNewMail) {
            if (!isset($newMail)) {
                newLogs("CREATE USER ERROR", "Adresse mail incorrect");
            } else {
                $stmt = $pdo->prepare("UPDATE users SET email = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
                $stmt->execute([$newMail, date('Y-m-d H:i:s'), $id, $id]);
                newLogs("EMAIL UPDATE", "Votre adresse mail modifié avec succès");
                newNotification("success", "votre adresse mail modifié avec succès", true, "fa-circle-check");
            }
        } else {
            newLogs("email update", "vos adresse mails ne correspondent pas");
            newNotification("error", "vos adresse mails ne correspondent pas", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("email update", "Adresse mail incorrect");
        newNotification("error", "Adresse mail incorrect", true, "fa-circle-exclamation");
    }
    header("Refresh: 0");
}

function updateUsername($id, $username)
{
    $username = trim(htmlspecialchars($username));
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $isUsername = $stmt->fetch();

    if (strlen($username) > 1 && strlen($username) < 16) {
        if ($isUsername) {
            newLogs("Username update", "Nom d'utilisateur déjà utilisé");
            newNotification("error", "Nom d'utilisateur déjà utilisé", true, "fa-circle-exclamation");
            header("Refresh: 0");
        } else {
            newLogs("Username update", "Nom d'utilisateur modifié avec succès");
            $stmt = $pdo->prepare("UPDATE users SET username = ?, updatedAt = ?, updatedBy = ? WHERE id = ?");
            $stmt->execute([$username, date("Y-m-d H:i:s"), $id, $id]);
            $_SESSION["user"]["username"] = $username;
            $_POST = array();
            newNotification("success", "Nom d'utilisateur modifié avec succès", true, "fa-circle-check");
            header("Refresh: 0");
        }
    } else {
        newLogs("Username update", "Nom d'utilisateur trop court");
        newNotification("error", "Nom d'utilisateur trop court", true, "fa-circle-exclamation");
        header("Refresh: 0");
    }
}

function addPost(string $title, string $description, int $postCategoryId, $photo, int $id)
{
    $title = htmlspecialchars(trim($title));
    $description = htmlspecialchars(trim($description));

    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM postCategory WHERE id = ?");
    $stmt->execute([$postCategoryId]);
    $isCategory = $stmt->fetch();

    $lastRef = $pdo->query("SELECT id FROM posts ORDER BY id desc limit 1")->fetchColumn();
    if ($lastRef === null) {
        $lastRef = 0;
    }
    $reference = "POS_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);

    if (!$isCategory) {
        newLogs("CREATE POST ERROR", "Catégorie de post inexistante");
        newNotification("error", "Catégorie de post inexistante", true, "fa-circle-exclamation");
        header("Refresh: 0");
        exit();
    } else {
        if (strlen($title) > 0 && strlen($title) < 51){
            if (strlen($description) > 0 && strlen($description) < 1000) {
                $sql = "INSERT INTO posts (title, description, postCategoryId, photo, reference, createdAt, createdBy) VALUES (?, ?, ?, ?, ?, ?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $description, $postCategoryId, $photo, $reference, date("Y-m-d H:i:s"), $id]);

                newLogs("CREATE POST", "Post créé avec succès : " . $title);
                header("Location: /index.php?page=post&ref=" . $reference);
            } else {
                newLogs("CREATE POST ERROR", "Description vide");
                newNotification("error", "Description vide", true, "fa-circle-exclamation");
                header("Refresh: 0");
            }
        } else {
            newLogs("CREATE POST ERROR", "Titre vide");
            newNotification("error", "Titre vide", true, "fa-circle-exclamation");
            header("Refresh: 0");
        }
    }
}

function reloadSession()
{
    if (isset($_SESSION["user"])) {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT users.username, users.email, users.image, users.roleId, users.biography, users.isBanned, users.bannedAt, users.bannedBy FROM users WHERE id = ?");
        $stmt->execute([$_SESSION["user"]["id"]]);
        $user = $stmt->fetch();
        $_SESSION["user"] = [
            "id" => $_SESSION["user"]["id"],
            "reference" => $_SESSION["user"]["reference"],
            "username" => $user["username"],
            "email" => $user["email"],
            "image" => $user["image"],
            "roleId" => $user["roleId"],
            "biography" => $user["biography"],
            "isBanned" => $user["isBanned"],
            "bannedAt" => $user["bannedAt"],
            "bannedBy" => $user["bannedBy"],
        ];

        if ($_SESSION["user"]["isBanned"] == 1) {
            session_destroy();
            newNotification("error", "Vous avez été banni !", true, "fa-circle-exclamation");
            header("Location: /index.php?page=home");
        }
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
        session_destroy();
        newLogs("DELETE USER", "Utilisateur supprimé : " . $id);
        newNotification("warning", "Votre compte a bien été supprimé", true, "fa-circle-check");
        header("Location: /index.php?page=home");
    } else {
        newLogs("DELETE USER", "Mot de passe incorrect");
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
                    return false;
                }
            } else {
                newLogs("error", "Erreur lors de l'upload de l'image (imageSize)");
                return false;
            }
        } else {
            newLogs("error", "Erreur lors de l'upload de l'image (imageFileType)");
            return false;
        }
    } else {
        newLogs("error", "Erreur lors de l'upload de l'image (error)");
        return false;
    }
}

function disconnect()
{
    session_destroy();
    newLogs("DISCONNECT", "Utilisateur déconnecté : " . $_SESSION["user"]["username"] . " - " . $_SESSION["user"]["email"]);
    newNotification("warning", "Vous êtes déconnecté avec succès.", true, "fa-person-walking");
    header("Location: /index.php?page=home");
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

function getAllPost($idPost, $isDeleted)
{
    $pdo = dbConnect();
    if ($isDeleted) {
        $sql = "SELECT * FROM posts";
        $sql = ($idPost == "all") ? $sql : $sql . " and id = ?";
        $stmt = $pdo->prepare($sql);

        if ($idPost != "all") {
            $var = [
                $idPost
            ];
        } else {
            $var = [];
        }
    } elseif (!$isDeleted) {
        $sql = "SELECT * FROM posts WHERE isDeleted = ?";
        $sql = ($idPost == "all") ? $sql : $sql . " and id = ?";
        $stmt = $pdo->prepare($sql);

        if ($idPost != "all") {
            $var = [
                ($isDeleted) ? 1 : 0,
                $idPost
            ];
        } else {
            $var = [
                ($isDeleted) ? 1 : 0,
            ];
        }
    }
    $stmt->execute($var);
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

function getUser(int $id, bool $order = false): array
{
    $pdo = DBConnect();

    if ($order) {
        $sql = "SELECT users.username, users.roleId, users.image, users.biography, users.email, users.id, users.reference, users.status, users.createdAt, users.creatdedBy, users.isActive, users.isDeleted, users.isBanned,  users.bannedAt,  users.bannedBy FROM users ORDER BY users.roleId DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tempUser = $stmt->fetchAll();
    } else {
        if ($id === -1) {
            $sql = "SELECT users.username, users.roleId, users.image, users.biography, users.email, users.id, users.reference, users.status, users.createdAt, users.creatdedBy, users.isActive, users.isDeleted, users.isBanned,  users.bannedAt,  users.bannedBy FROM users";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $tempUser = $stmt->fetchAll();
        } else {
            $sql = "SELECT users.username, users.roleId, users.image, users.biography, users.email, users.id, users.reference, users.status, users.createdAt, users.creatdedBy, users.isActive, users.isDeleted, users.isBanned,  users.bannedAt,  users.bannedBy FROM users WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $tempUser = $stmt->fetch();
        }
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


function addCategory(string $name, int $id, string $icon)
{
    $name = htmlspecialchars(trim($name));

    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM postCategory where name = ?");
    $stmt->execute([$name]);

    $category = $stmt->fetch();

    if ($category) {
        newLogs("CREATE CATEGORY", "Catégorie déjà existante : " . $name);
        newNotification("error", "Catégorie déjà existante", true, "fa-circle-exclamation");
    } else {
        newLogs("CREATE CATEGORY", "Catégorie ajoutée : " . $name);

        $lastRef = $pdo->query("SELECT id FROM postCategory ORDER BY id desc limit 1")->fetchColumn();
        if ($lastRef === null) {
            $lastRef = 0;
        }

        $reference = "CAT_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);

        $stmt = $pdo->prepare("INSERT INTO postCategory (name, reference, createdAt, createdBy, icons) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $reference, date("Y-m-d H:i:s"), $id, $icon]);

        newNotification("success", "Catégorie ajoutée avec succès", true, "fa-circle-check");
    }
    header("Refresh: 0");
}

function getCategory(int $id)
{
    $pdo = dbConnect();

    if ($id === -1) {
        $sql = "SELECT * FROM postCategory";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $category = $stmt->fetchAll();
    } else {
        $sql = "SELECT * FROM postCategory WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $category = $stmt->fetch();
    }

    return $category;
}

function getNbComments($id)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT COUNT(*) as nbComments FROM comments WHERE postId = ? and isActive = 1 and isDeleted = 0");
    $stmt->execute([$id]);
    $nbC = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT COUNT(*) as nbComments FROM sous_comments WHERE postId = ? and isActive = 1 and isDeleted = 0");
    $stmt->execute([$id]);
    $nbSC = $stmt->fetch();

    return [
        "nbComments" => $nbC["nbComments"] + $nbSC["nbComments"]
    ];
}


function loginRestore($id)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $isUser = $stmt->fetch();

    if ($isUser) {
        $sql = "UPDATE users SET users.isActive = 1, users.isDeleted = 0 WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        unset($_COOKIE["status"]);
        setcookie("status", "", time() - 3600, "/public/views/login.php");

        newLogs("RESTORE USER", "Utilisateur restauré : " . $id);
        newNotification("success", "Utilisateur restauré avec succès", true, "fa-circle-check");
        header("Refresh: 0");
    }
}


function addComment($message, $postId, $reference, $id)
{
    $message = htmlspecialchars(trim($message));

    $pdo = dbConnect();

    $lastRef = $pdo->query("SELECT id FROM comments ORDER BY id desc limit 1")->fetchColumn();
    if ($lastRef === null) {
        $lastRef = 0;
    }
    $reference = "COM_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);

    $sql = "INSERT INTO comments (message, postId , reference, createdAt, createdBy) values (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$message, $postId, $reference, date("Y-m-d H:i:s"), $id]);

    newNotification("success", "Commentaire publié !", true, "fa-circle-check");

    header("Refresh: 0");
}

function getNbPosts($id)
{
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) as nbPosts FROM posts WHERE createdBy = ? and isActive = 1 and isDeleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

function getNbUsers($id)
{
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) as nbUsers FROM users WHERE roleId = ? and isActive = 1 and isDeleted = 0";
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

    $stmt = $pdo->prepare("SELECT COUNT(*) as nbComments FROM comments WHERE createdBy = ?");
    $stmt->execute([$id]);
    $nbC = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT COUNT(*) as nbComments FROM sous_comments WHERE createdBy = ?");
    $stmt->execute([$id]);
    $nbSC = $stmt->fetch();

    return [
        "nbComments" => $nbC["nbComments"] + $nbSC["nbComments"]
    ];
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
    $sql = "SELECT * FROM posts WHERE reference = ? and isDeleted = 0";
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

function getCommentsWherePOS($id)
{
    $pdo = dbConnect();

    $sql = "SELECT * FROM comments WHERE postId = ? and isActive = 1 and isDeleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function getNbCommentForUser($idUser)
{
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) as nbComments FROM comments WHERE createdBy = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUser]);

    return $stmt->fetch();
}

function addRespondComment($message, $commentId, $reference, $userId, $postId)
{
    $pdo = dbConnect();

    $message = htmlspecialchars(trim($message));

    $lastRef = $pdo->query("SELECT id FROM sous_comments ORDER BY id desc limit 1")->fetchColumn();
    if ($lastRef === null) {
        $lastRef = 0;
    }
    $reference = "RCO_" . str_pad($lastRef + 1, 4, "0", STR_PAD_LEFT);

    $sql = "INSERT INTO sous_comments (message, commentId, reference, createdAt, createdBy, postId) values (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$message, $commentId, $reference, date("Y-m-d H:i:s"), $userId, $postId]);

    newNotification("success", "Commentaires publiés", true, "fa-circle-check");
    $_POST = array();
    header("Refresh: 0");
}

function deletePost(int $idPost, int $idUser)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT posts.id, posts.createdBy FROM posts WHERE id = ?");
    $stmt->execute([$idPost]);
    $isPost = $stmt->fetch();

    if ($isPost) {
        if ($isPost["createdBy"] == $idUser) {
            $stmt = $pdo->prepare("UPDATE posts SET isDeleted = 1, isActive = 0, updatedAt = ?, updatedBy = ?, status = 'c' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $idUser, $idPost]);
            newLogs("DELETE POST", "Post supprimé : " . $idPost);
            newNotification("success", "Post supprimé avec succès !", true, "fa-circle-check");
        } else {
            newLogs("DELETE POST", "Utilisateur non autorisé : " . $idUser);
            newNotification("error", "Erreur lors de la modification du post.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("DELETE POST", "Post innexistant : " . $idPost);
        newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
    }

    $_POST = array();
    header("Refresh: 0");
}

function restorePost(int $idPost, int $idUser)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT posts.id, posts.createdBy FROM posts WHERE id = ?");
    $stmt->execute([$idPost]);
    $isPost = $stmt->fetch();

    if ($isPost) {
        if ($isPost["createdBy"] == $idUser) {
            $stmt = $pdo->prepare("UPDATE posts SET isDeleted = 0, isActive = 1, updatedAt = ?, updatedBy = ?, status = 'a' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $idUser, $idPost]);
            newLogs("RESTORE POST", "Post restauré : " . $idPost);
            newNotification("success", "Post restauré avec succès !", true, "fa-circle-check");
        } else {
            newLogs("RESTORE POST", "Utilisateur non autorisé : " . $idUser);
            newNotification("error", "Erreur lors de la modification du post.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("RESTORE POST", "Post innexistant : " . $idPost);
        newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
    }

    $_POST = array();
    header("Refresh: 0");
}

function hidePost(int $idPost, int $idUser)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT posts.id, posts.createdBy FROM posts WHERE id = ?");
    $stmt->execute([$idPost]);
    $isPost = $stmt->fetch();

    if ($isPost) {
        if ($isPost["createdBy"] == $idUser) {
            $stmt = $pdo->prepare("UPDATE posts SET isActive = 0, updatedAt = ?, updatedBy = ?, status = 'b' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $idUser, $idPost]);
            newLogs("HIDE POST", "Post caché : " . $idPost);
            newNotification("warning", "Post caché avec succès !", true, "fa-eye-slash");
        } else {
            newLogs("HIDE POST", "Utilisateur non autorisé : " . $idUser);
            newNotification("error", "Erreur lors de la modification du post.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("HIDE POST", "Post innexistant : " . $idPost);
        newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
    }

    $_POST = array();
    header("Refresh: 0");
}

function showPost(int $idPost, int $idUser)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT posts.id, posts.createdBy FROM posts WHERE id = ?");
    $stmt->execute([$idPost]);
    $isPost = $stmt->fetch();

    if ($isPost) {
        if ($isPost["createdBy"] == $idUser) {
            $stmt = $pdo->prepare("UPDATE posts SET isActive = 1, updatedAt = ?, updatedBy = ?, status = 'a' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $idUser, $idPost]);
            newLogs("SHOW POST", "Post affiché : " . $idPost);
            newNotification("success", "Post affiché avec succès !", true, "fa-circle-check");
        } else {
            newLogs("SHOW POST", "Utilisateur non autorisé : " . $idUser);
            newNotification("error", "Erreur lors de la modification du post.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("SHOW POST", "Post innexistant : " . $idPost);
        newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
    }

    $_POST = array();
    header("Refresh: 0");
}


function deletePostAdmin(int $idPost, int $userLevel)
{
    $pdo = dbConnect();
    if ($userLevel > 1) {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT posts.id FROM posts WHERE id = ?");
        $stmt->execute([$idPost]);
        $isPost = $stmt->fetch();

        if ($isPost) {
            $stmt = $pdo->prepare("UPDATE posts SET isDeleted = 1, isActive = 0, updatedAt = ?, updatedBy = ?, status = 'd' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userLevel, $idPost]);
            newLogs("DELETE POST", "Post supprimé : " . $idPost);
            newNotification("success", "Post supprimé avec succès !", true, "fa-circle-check");
        } else {
            newLogs("DELETE POST", "Post innexistant : " . $idPost);
            newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("DELETE POST", "Utilisateur non autorisé : " . $userLevel);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
}

function restorePostAdmin(int $idPost, int $userLevel)
{
    $pdo = dbConnect();
    if ($userLevel > 2) {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT posts.id FROM posts WHERE id = ?");
        $stmt->execute([$idPost]);
        $isPost = $stmt->fetch();

        if ($isPost) {
            $stmt = $pdo->prepare("UPDATE posts SET isDeleted = 0, isActive = 1, updatedAt = ?, updatedBy = ?, status = 'a' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userLevel, $idPost]);
            newLogs("RESTORE POST", "Post restauré : " . $idPost);
            newNotification("success", "Post restauré avec succès !", true, "fa-circle-check");
        } else {
            newLogs("RESTORE POST", "Post innexistant : " . $idPost);
            newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("RESTORE POST", "Utilisateur non autorisé : " . $userLevel);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
}

function hidePostAdmin(int $idPost, int $userLevel)
{
    $pdo = dbConnect();
    if ($userLevel > 1) {
        $stmt = $pdo->prepare("SELECT posts.id FROM posts WHERE id = ?");
        $stmt->execute([$idPost]);
        $isPost = $stmt->fetch();

        if ($isPost) {
            $stmt = $pdo->prepare("UPDATE posts SET isActive = 0, updatedAt = ?, updatedBy = ?, status = 'b' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userLevel, $idPost]);
            newLogs("HIDE POST", "Post caché : " . $idPost);
            newNotification("warning", "Post caché avec succès !", true, "fa-eye-slash");
        } else {
            newLogs("HIDE POST", "Post innexistant : " . $idPost);
            newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("HIDE POST", "Utilisateur non autorisé : " . $userLevel);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
}

function showPostAdmin(int $idPost, int $userLevel)
{
    $pdo = dbConnect();
    if ($userLevel > 1) {
        $stmt = $pdo->prepare("SELECT posts.id FROM posts WHERE id = ?");
        $stmt->execute([$idPost]);
        $isPost = $stmt->fetch();

        if ($isPost) {
            $stmt = $pdo->prepare("UPDATE posts SET isActive = 1, updatedAt = ?, updatedBy = ?, status = 'a' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userLevel, $idPost]);
            newLogs("SHOW POST", "Post affiché : " . $idPost);
            newNotification("success", "Post affiché avec succès !", true, "fa-circle-check");
        } else {
            newLogs("SHOW POST", "Post innexistant : " . $idPost);
            newNotification("error", "Post innexistant.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("SHOW POST", "Utilisateur non autorisé : " . $userLevel);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
}

function getRCOWhereCOM($id)
{
    $pdo = dbConnect();

    $sql = "SELECT * FROM sous_comments WHERE commentId = ? and isActive = 1 and isDeleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function searchMyPost(int $idUser, string $search)
{
    $search = trim(htmlspecialchars($search));
    $pdo = dbConnect();
    $sql = "SELECT * FROM posts WHERE CONCAT(title, id) LIKE ? and createdBy = ? ORDER BY createdAt ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%", $idUser]);
    return $stmt->fetchAll();
}

function searchUser(string $search)
{
    $search = trim(htmlspecialchars($search));
    $pdo = dbConnect();
    $sql = "SELECT * FROM users WHERE CONCAT(username, id) LIKE ? ORDER BY roleId DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%"]);
    return $stmt->fetchAll();
}

function searchCategory(string $search)
{
    $search = trim(htmlspecialchars($search));
    $pdo = dbConnect();
    $sql = "SELECT * FROM postCategory WHERE CONCAT(name, id) LIKE ? ORDER BY createdAt ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%"]);
    return $stmt->fetchAll();
}

function searchPost(string $search)
{
    $search = trim(htmlspecialchars($search));
    $pdo = dbConnect();
    $sql = "SELECT * FROM posts WHERE CONCAT(title, id) LIKE ? ORDER BY createdAt ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%"]);
    return $stmt->fetchAll();
}

function searchRole(string $search)
{
    $search = trim(htmlspecialchars($search));
    $pdo = dbConnect();
    $sql = "SELECT * FROM roles WHERE CONCAT(name, id) LIKE ? ORDER BY createdAt ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%"]);
    return $stmt->fetchAll();
}

function banUser(int $idUserBanned, array $userRequest, string $reason)
{
    if (isset($idUserBanned, $userRequest, $reason)) {
        if ($userRequest["roleId"] > 1) {
            $pdo = dbConnect();
            $UserBanned = getUser($idUserBanned);
            $reason = htmlspecialchars(trim($reason));

            if ($UserBanned) {
                if ($userRequest["roleId"] > $UserBanned["roleId"]) {
                    $stmt = $pdo->prepare("UPDATE users SET isBanned = 1, bannedAt = ?, bannedBy = ? WHERE id = ?");
                    $stmt->execute([date("Y-m-d H:i:s"), $userRequest["id"], $UserBanned["id"]]);
                    newNotification("success", "L'utilisateur a bien été banni", true, "fa-circle-check");
                    newLogs("BAN", "Utilisateur banni : " . $UserBanned["username"] . ", raison : " . $reason . " (Banni par {$userRequest["username"]}(#{$userRequest["id"]})");
                    header("Refresh: 0");
                } else {
                    newNotification('error', "Vous ne pouvez pas bannir cet utilisateur", true, "fa-circle-exclamation");
                    header("Refresh: 0");
                }
            } else {
                newNotification("error", "L'utilisateur n'existe pas", true, "fa-circle-exclamation");
                header("Refresh: 0");
            }
        } else {
            newNotification('error', "Vous n'avez pas les permissions nécessaires", true, "fa-circle-exclamation");
            header("Refresh: 0");
        }
    }
}

function unbanUser(int $idUserBanned, array $userRequest)
{
    $pdo = dbConnect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$idUserBanned]);
    $isUser = $stmt->fetch();

    if ($isUser) {
        $userBanned = getUser($idUserBanned);
        $userWhoBan = getUser($userBanned["bannedBy"]);

        if ($userRequest["roleId"] > $userBanned["roleId"]) {
            if ($userRequest["roleId"] >= $userWhoBan["roleId"]) {
                $stmt = $pdo->prepare("UPDATE users SET isBanned = 0 WHERE id = ?");
                $stmt->execute([$idUserBanned]);
                newNotification("success", "{$userBanned["username"]} a bien été débanni", true, "fa-circle-check");
                newLogs("UNBAN", "Utilisateur débanni : " . $userBanned["username"] . " (Débanni par {$userRequest["username"]}(#{$userRequest["id"]})");
                header("Refresh: 0");
            } else {
                newNotification("error", "Vous ne pouvez pas débannir cet utilisateur", true, "fa-circle-exclamation");
                header("Refresh: 0");
            }
        } else {
            newNotification("error", "Vous ne pouvez pas débannir cet utilisateur", true, "fa-circle-exclamation");
            header("Refresh: 0");
        }
    } else {
        newNotification("error", "L'utilisateur n'existe pas", true, "fa-circle-exclamation");
        header("Refresh: 0");
    }
}

function deleteCatAdmin(int $idCat, array $userRequest)
{
    $pdo = dbConnect();
    if ($userRequest["roleId"] > 1) {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT id, name FROM postCategory WHERE id = ?");
        $stmt->execute([$idCat]);
        $isCategory = $stmt->fetch();

        if ($isCategory) {
            $stmt = $pdo->prepare("UPDATE postCategory SET isDeleted = 1, isActive = 0, updatedAt = ?, updatedBy = ?, status = 'd' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userRequest["id"], $isCategory["id"]]);
            newLogs("DELETE CAT", "Catégorie supprimée : {$isCategory['name']}(#{$isCategory['id']})");
            newNotification("success", "Catégorie supprimée avec succès !", true, "fa-circle-check");
        } else {
            newLogs("DELETE CAT", "Catégorie innexistante : #{$idCat}");
            newNotification("error", "Catégorie innexistante.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("DELETE CAT", "Utilisateur non autorisé : " . $userRequest["id"]);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }
    header("Refresh: 0");
}

function restoreCatAdmin(int $idCat, array $userRequest)
{
    $pdo = dbConnect();
    if ($userRequest["roleId"] > 1) {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT id, 'name' FROM postCategory WHERE id = ?");
        $stmt->execute([$idCat]);
        $isCategory = $stmt->fetch();

        if ($isCategory) {
            $stmt = $pdo->prepare("UPDATE postCategory SET isDeleted = 0, isActive = 1, updatedAt = ?, updatedBy = ?, status = 'a' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userRequest["id"], $isCategory["id"]]);
            newLogs("RESTORE CAT", "Catégorie restaurée : {$isCategory['name']}(#{$isCategory['id']})");
            newNotification("success", "Catégorie restaurée avec succès !", true, "fa-circle-check");
        } else {
            newLogs("RESTORE CAT", "Catégorie innexistante : " . $isCategory);
            newNotification("error", "Catégorie innexistante.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("RESTORE CAT", "Utilisateur non autorisé : " . $userRequest["id"]);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
}

function hideCatAdmin(int $idCat, int $userLevel)
{
    $pdo = dbConnect();
    if ($userLevel > 1) {
        $stmt = $pdo->prepare("SELECT id FROM postCategory WHERE id = ?");
        $stmt->execute([$idCat]);
        $isCat = $stmt->fetch();

        if ($isCat) {
            $stmt = $pdo->prepare("UPDATE postCategory SET isActive = 0, updatedAt = ?, updatedBy = ?, status = 'b' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userLevel, $idCat]);
            newLogs("HIDE CAT", "Catégorie cachée : " . $idCat);
            newNotification("warning", "Catégorie cachée avec succès !", true, "fa-eye-slash");
        } else {
            newLogs("HIDE CAT", "Catégorie innexistante : " . $idCat);
            newNotification("error", "Catégorie innexistante.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("HIDE CAT", "Utilisateur non autorisé : " . $userLevel);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
}

function showCatAdmin(int $idCat, int $userLevel)
{
    $pdo = dbConnect();
    if ($userLevel > 1) {
        $stmt = $pdo->prepare("SELECT id FROM postCategory WHERE id = ?");
        $stmt->execute([$idCat]);
        $isCat = $stmt->fetch();

        if ($isCat) {
            $stmt = $pdo->prepare("UPDATE postCategory SET isActive = 0, updatedAt = ?, updatedBy = ?, status = 'b' WHERE id = ?");
            $stmt->execute([date("Y-m-d H:i:s"), $userLevel, $idCat]);
            newLogs("SHOW CAT", "Catégorie affichée : " . $idCat);
            newNotification("warning", "Catégorie affichée avec succès !", true, "fa-eye-slash");
        } else {
            newLogs("SHOW CAT", "Catégorie innexistante : " . $idCat);
            newNotification("error", "Catégorie innexistante.", true, "fa-circle-exclamation");
        }
    } else {
        newLogs("SHOW CAT", "Utilisateur non autorisé : " . $userLevel);
        newNotification("error", "Vous n'avez pas les droits pour effectuer cette action.", true, "fa-circle-exclamation");
    }

    header("Refresh: 0");
}

function getLogs()
{

    $pdo = dbConnect();

    $sql = "SELECT * FROM logs";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $logs = $stmt->fetchAll();

    return $logs;
}

function searchLogs(string $search)
{
    $search = trim(htmlspecialchars($search));
    $pdo = dbConnect();
    $sql = "SELECT * FROM logs WHERE CONCAT(type, logs) LIKE ? ORDER BY createdAt ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%"]);
    return $stmt->fetchAll();
}

function searchAll(string $search)
{
    $search = trim(htmlspecialchars($search));
    $pdo = dbConnect();
    $sql = "SELECT * FROM posts WHERE CONCAT(title) LIKE ? ORDER BY createdAt ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%"]);
    return $stmt->fetchAll();
}

<?php

if (!isset($_GET["ref"])) {
    header("Location: /index.php?page=home");
} elseif (!getPostByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$post = getPostByRef($_GET["ref"]);
$userCreator = getUser($post["createdBy"]);
$comments = getCommentsWherePOS($post["id"]);

// print_r($comments);

if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["submitMsg"])) {
    $status = addComment($_POST["comment"], $post["id"], $post["reference"], $_SESSION["user"]["id"]);

    if ($status["type"] === "success") {
        echo "<script>window.location.href = 'index.php?page=viewpost&ref=" . $_GET["ref"] . "&success=1&message=" . $status["message"] . "';</script>";
    } else {
        echo "<script>window.location.href = 'index.php?page=viewpost&ref=" . $_GET["ref"] . "&error=1&message=" . $status["message"] . "';</script>";
    }
}

?>

<div class="h-screen">
    <div class="w-[80%] min-h-lvh h-[80%] mt-[5%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden">
        <div class="space-y-5">
            <div class="*:min-w-full *:h-96 *:rounded-lg">
                <?php if (!$post["photo"] == "") : ?>
                    <img src="<?= $post["photo"] ?>" alt="">
                <?php else : ?>
                    <img src="https://via.placeholder.com/600" alt="">
                <?php endif; ?>
            </div>
            <div class="space-y-2">
                <span>
                    <h2 class="mt-2 text-3xl text-gray-800 font-bold"><?= ucfirst($post["title"]) ?></h2>
                    <span class="uppercase text-indigo-500 text-sm font-bold tracking-wide"><?= getCategory($post["postCategoryId"])["name"] ?></span>
                </span>
                <p class="text-gray-700 text-md font-normal text-pretty text-justify tracking-wide"><?= $post["description"] ?></p>
                <span class="text-gray-600 text-sm italic">Publié le <?= date("d/m/Y", strtotime($post["createdAt"])) ?> à <?= date("H:i", strtotime($post["createdAt"])) ?></span>
            </div>
            <div class="flex flex-col flex-nowrap text-justify ">
                <span class="text-gray-700 text-sm font-medium text-left text-normal mb-2">Créer par,</span>
                <a href="/index.php?page=profil&ref=<?= $userCreator["reference"] ?>">
                    <div class="flex items-center gap-4 bg-white px-2 py-3 w-fit group hover:scale-105 hover:bg-gray-50 rounded-lg transition-all duration-75">
                        <?php if (isset($userCreator["image"]) && $userCreator["image"] != "") : ?>
                            <img src="<?= $userCreator["image"] ?>" alt="avatar" class="relative shadow-sm-light inline-block object-cover object-center w-12 h-12 rounded-lg" />
                        <?php else : ?>
                            <span class="bg-gray-500 flex items-center text-3xl shadow-sm-light rounded-lg text-white w-12 h-12 justify-center">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        <?php endif; ?>
                        <div>
                            <h6 class="block font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-inherit">
                                <?= $userCreator["username"] ?>
                            </h6>
                            <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-700">
                                <?= getRole($userCreator["roleId"])["name"] ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="mt-10">
            <h3 class="text-xl font-bold">Commentaires :</h3>
        </div>
        <form method="post">
            <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                    <label for="comment" class="sr-only">Commentaire</label>
                    <textarea id="comment" name="comment" rows="3" minlength="1" class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="Message" required></textarea>
                </div>
                <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
                    <button type="submit" name="submitMsg" value="1" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                        Envoyer
                    </button>
                    <div class="flex ps-0 space-x-1 rtl:space-x-reverse sm:ps-2">
                    </div>
                </div>
            </div>
        </form>
        <div>
            <article>
                <?php foreach ($comments as $comment) { ?>
                    <div class="flex items-center mb-4">
                        <img class="w-10 h-10 me-4 rounded-full" src="<?= getUser($comment["createdBy"])["image"] ?>" alt="">
                        <div class="font-medium dark:text-white">
                            <p><?= getUser($comment["createdBy"])["username"] ?></p>
                        </div>
                    </div>
                    <footer class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                        <p><?= $comment["createdAt"] ?></p>
                    </footer>
                    <p class="mb-2 text-gray-500 dark:text-gray-400"><?= $comment["message"] ?></p>
                    <!--                    <aside>-->
                    <!--                        <div class="flex items-center mt-3">-->
                    <!--                            <a href="#" class="ps-4 text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 border-gray-200 ms-4 border-s md:mb-0 dark:border-gray-600">Report abuse</a>-->
                    <!--                        </div> -->
                    <!--                    </aside>-->
                <?php
                }
                ?>
            </article>
        </div>
    </div>
</div>
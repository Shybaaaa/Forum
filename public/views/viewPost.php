<?php

if (!isset($_GET["ref"]) || !getPostByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$post = getPostByRef($_GET["ref"]);

if ($_SESSION["user"]["roleId"] < 2) {
    if (!$post["isActive"] && $_SESSION["user"]["id"] != $post["createdBy"]) {
        header("Location: /index.php?page=home");
    }
}

$userCreator = getUser($post["createdBy"]);
$comments = getCommentsWherePOS($post["id"]);

// print_r($comments);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST) {
        case isset($_POST["submitMsg"]):
            addComment($_POST["comment"], $post["id"], $post["reference"], $_SESSION["user"]["id"]);
            break;
        case isset($_POST["commentrespond"]):
            addRespondComment($_POST["message"], $_POST["commentId"], $post["reference"], $_SESSION["user"]["id"]);
            break;
    }
}


?>

<script>
    if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.querySelector('html').classList.add('dark');
    } else {
        document.querySelector('html').classList.remove('dark');
    }
</script>

<div class="h-screen">
    <div class="w-[80%] h-[90%] mt-[2%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden dark:bg-slate-700">
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
                    <h2 class="mt-2 text-3xl text-gray-800 font-bold dark:text-slate-200"><?= ucfirst($post["title"]) ?></h2>
                    <span class="uppercase text-indigo-500 text-sm font-bold tracking-wide"><?= getCategory($post["postCategoryId"])["name"] ?></span>
                </span>
                <p class="text-gray-700 dark:text-slate-200 text-md font-normal text-pretty text-justify tracking-wide"><?= $post["description"] ?></p>
                <span class="text-gray-600 text-sm italic dark:text-slate-400 ">Publié le <?= date("d/m/Y", strtotime($post["createdAt"])) ?> à <?= date("H:i", strtotime($post["createdAt"])) ?></span>
            </div>
            <div class="flex flex-col flex-nowrap text-justify ">
                <span class="text-gray-700 text-sm font-medium text-left text-normal mb-2 dark:text-slate-200">Créer par,</span>
                <a href="/index.php?page=profil&ref=<?= $userCreator["reference"] ?>">
                    <div class="flex items-center gap-4 bg-white px-2 py-3 w-fit group hover:scale-105 hover:bg-gray-50 rounded-lg transition-all duration-75 dark:bg-slate-700">
                        <?php if (isset($userCreator["image"]) && $userCreator["image"] != "") : ?>
                            <img src="<?= $userCreator["image"] ?>" alt="avatar" class="relative shadow-sm-light inline-block object-cover object-center w-12 h-12 rounded-lg" />
                        <?php else : ?>
                            <span class="bg-gray-500 flex items-center text-3xl shadow-sm-light rounded-lg text-white w-12 h-12 justify-center">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        <?php endif; ?>
                        <div>
                            <h6 class="block font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-inherit dark:text-slate-300">
                                <?= $userCreator["username"] ?>
                            </h6>
                            <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-700 dark:text-slate-200">
                                <?= getRole($userCreator["roleId"])["name"] ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="mt-10 dark:text-slate-300">
            <h3 class="text-xl font-bold">Commentaires :</h3>
        </div>
        <?php if (isset($_SESSION["user"])) : ?>
            <form method="post">
                <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                        <label for="comment" class="sr-only">Commentaire</label>
                        <textarea id="comment" name="comment" rows="2" minlength="1" class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="Message" required></textarea>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
                        <button type="submit" name="submitMsg" value="1" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                            Envoyer
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
        <div class="w-full">
            <?php foreach ($comments as $comment) : ?>
                <article class="bg-gray-100 w-1/2 rounded-lg px-6 py-2 my-3 ml-3 dark:bg-slate-600">
                    <div class="flex items-center mb-4">
                        <a href="index.php?page=profil&ref=<?= getUser($comment["createdBy"])["reference"] ?>" class="flex items-center group transition-all">
                            <img class="w-10 h-10 me-4 rounded-full transition duration-75" src="<?= getUser($comment["createdBy"])["image"] ?>" alt="">
                            <div class="font-medium group-hover:text-indigo-600 transition duration-75 dark:text-white">
                                <p><?= getUser($comment["createdBy"])["username"] ?></p>
                            </div>
                        </a>
                        <p class="ml-3 text-gray-500 text-sm text-pretty dark:text-slate-300"> <?= date("d/m/Y", strtotime($comment["createdAt"])) ?> à <?= date("H:i", strtotime($comment["createdAt"])) ?></p>
                        <button data-modal-target="commentRespond" data-modal-show="commentRespond" onclick="respondComment(<?= $comment['id'] ?>)" class="fa-solid fa-reply px-6 py-5"></button>
                    </div>
                    <p class="mb-2 text-gray-700 text-sm dark:text-gray-200"><?= $comment["message"] ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<div id="commentRespond" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Répondre au commentaire
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="commentRespond">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="flex items-center h-max flex-col justify-center w-full">
                    <div class="mb-6 w-full">
                        <label for="commentrespond" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                        <input type="text" id="commentId" readonly name="commentId" placeholder="Id" class="cursor-not-allowed text-gray-700 bg-gray-100 hidden">
                        <textarea id="commentrespond" name="message" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" maxlength="500" cols="30" rows="2"><?= $_SESSION["user"]["biography"] ?></textarea>
                        <div id="textareaBError" class="counter"><span id="counter">0</span> caractères (500 max)</div>
                    </div>
                    <div class="mt-3 w-full flex justify-end">
                        <input name="commentrespond" type="submit" value="Répondre" class="py-2 px-3 bg-gradient-to-tl to-indigo-600 from-blue-500 cursor-pointer text-medium text-white font-medium rounded-lg hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/public/js/viewpost.js"></script>
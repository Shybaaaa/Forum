<?php

if(!isset($_GET["ref"])) {
    header("Location: /index.php?page=home");
} elseif (!getPostByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$post = getPostByRef($_GET["ref"]);
$userCreator = getUser($post["createdBy"]);
$comments = getCommentsWherePOS($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = trim(htmlspecialchars($_POST["comment"] ?? ""));
    addComment($message, $post["id"], $post["reference"], $_SESSION["user"]["id"]);
}

?>

<div class="h-screen">
    <div class="w-[80%] min-h-lvh h-[80%] mt-[5%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-6 py-4 overflow-x-hidden">
        <div class="space-y-5">
            <div class="*:min-w-full *:h-96 *:rounded-lg">
                <?php if (!$post["photo"] == ""): ?>
                    <img src="<?= $post["photo"] ?>" alt="">
                <?php else: ?>
                    <img src="https://via.placeholder.com/600" alt="">
                <?php endif; ?>
            </div>
            <div class="space-y-2">
                <span>
                    <h2 class="mt-2 text-3xl text-gray-800 font-bold"><?= ucfirst($post["title"])?></h2>
                    <span class="uppercase text-indigo-500 text-sm font-bold tracking-wide"><?= getCategory($post["postCategoryId"])["name"] ?></span>
                </span>
                <p class="text-gray-700 text-md font-normal text-pretty text-justify tracking-wide"><?= $post["description"] ?></p>
                <span class="text-gray-600 text-sm italic">Publié le <?= date("d/m/Y", strtotime($post["createdAt"])) ?> à <?= date("H:i", strtotime($post["createdAt"])) ?></span>
            </div>
            <div class="flex flex-col flex-nowrap text-justify ">
                <span class="text-gray-700 text-sm font-medium text-left text-normal mb-2">Créer par,</span>
                <a href="/index.php?page=profil&ref=<?= $userCreator["reference"] ?>">
                    <div class="flex items-center gap-4 bg-white px-2 py-3 w-fit group hover:scale-105 hover:bg-gray-50 rounded-lg transition-all duration-75">
                        <?php if(isset($userCreator["image"]) && $userCreator["image"] != ""): ?>
                            <img src="<?= $userCreator["image"]?>" alt="avatar" class="relative shadow-sm-light inline-block object-cover object-center w-12 h-12 rounded-lg" />
                        <?php else: ?>
                            <span class="bg-gray-500 flex items-center text-3xl shadow-sm-light rounded-lg text-white w-12 h-12 justify-center">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        <?php endif; ?>
                        <div>
                            <h6 class="block font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-inherit">
                                <?= $userCreator["username"]?>
                            </h6>
                            <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-700">
                                <?= getRole($userCreator["roleId"])["name"]?>
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
                    <textarea id="comment" name="comment" rows="4"
                    class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                    placeholder="Message" required></textarea>
                </div>
                <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
                    <button type="submit"
                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                Envoyer
                    </button>
                    <div class="flex ps-0 space-x-1 rtl:space-x-reverse sm:ps-2">
                        <button type="button"
                        class="inline-flex justify-center items-center p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 12 20">
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                            d="M1 6v8a5 5 0 1 0 10 0V4.5a3.5 3.5 0 1 0-7 0V13a2 2 0 0 0 4 0V6"/>
                            </svg>
                            <span class="sr-only">Attach file</span>
                        </button>
                        <button type="button"
                        class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z"/>
                            </svg>
                            <span class="sr-only">Add emoji</span>
                        </button>
                        <button type="button"
                        class="inline-flex justify-center items-center p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 18">
                                <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                            </svg>
                            <span class="sr-only">Upload image</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div>

        </div>
    </div>
</div>
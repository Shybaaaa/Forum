<?php

if(!isset($_GET["ref"])) {
    header("Location: /index.php?page=home");
} elseif (!getUserByRef($_GET["ref"])) {
    header("Location: /index.php?page=home");
}

$user = getUserByRef($_GET["ref"]);

print_r($user);

?>

<div class="h-screen">
    <div class="w-[80%] min-h-lvh h-[80%] mt-[5%] rounded-lg shadow-md space-y-4 max-h-fit bg-white mx-auto gap-x-7 gap-y-9 px-2 py-2 overflow-x-hidden">
        <div class="w-full h-[20%] bg-gray-200 rounded-md"></div>
        <div>
            <div class="flex flex-row items-center -translate-y-1/2 justify-center gap-x-4">
                <div class="w-[15%] h-[15%] flex flex-col space-y-3 items-center">
                    <?php if(isset($user["image"]) && $user["image"] != ""): ?>
                        <img src="<?= $user["image"] ?>" alt="avatar" class="relative shadow-md border-2 inline-block object-cover object-center h-[160px] w-auto min-w-full min-h-full rounded-full" />
                    <?php else: ?>
                        <span class="bg-gray-400 flex items-center text-8xl shadow-md rounded-full text-white min-w-full min-h-full justify-center">
                            <i class="fa-solid p-8 fa-user"></i>
                        </span>
                    <?php endif; ?>
                    <div class="inline-flex text-center flex-col items-center ">
                        <span class="text-2xl font-semibold"><?= $user["username"] ?></span>
                        <span class="uppercase text-sm font-semibold"><?= getRole($user["roleId"])["name"] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

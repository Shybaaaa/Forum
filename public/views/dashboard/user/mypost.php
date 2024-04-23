<?php

$posts = getPostUser($_SESSION["user"]["id"], "all", true);

?>

<div class="w-10/12 h-[85%] shadow bg-white px-3.5 rounded-lg py-2.5">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <div class="relative m-[2px] mb-3 mr-5 float-left">
                <label for="inputSearch" class="sr-only">Rechercher</label>
                <input id="inputSearch" type="text" placeholder="Recherche..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400"/>
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </span>
            </div>
            <div class="flex m-[2px] float-right">
                <a href="../../../public/views/insert_post.php" type="button" class="bg-indigo-500 py-2.5 px-2 text-white rounded-lg font-medium hover:bg-indigo-500 hover:opacity-95 transition duration-75">
                    <i class="fa-solid fa-circle-plus text-sm text-white mr-1"></i>
                    Créer un post
                </a>
            </div>
            <table class="min-w-full text-left text-xs whitespace-nowrap">
                <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600">
                <tr>
                    <th scope="col" class="px-6 py-5">
                        Titre
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Catégorie
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Commentaires
                    </th>
                    <th scope="col" class="px-6 py-5">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    <tr class="border-b dark:border-neutral-600">
                        <th scope="row" class="px-6 py-5"><a href="/index.php?page=viewpost&ref=<?= $post["reference"] ?>" title="Vers le post"><?= $post["title"] ?></a></th>
                        <td class="px-6 py-5"><?= ucfirst(getCategory($post["postCategoryId"])["name"]) ?></td>
                        <td class="px-6 py-5">
                            <?php if ($post["status"] == "a"): ?>
                                <span class="text-green-500 font-bold text-sm">En ligne</span>
                            <?php elseif ($post["status"] == "b"): ?>
                                <span class="text-orange-300 font-bold text-sm">Masqué</span>
                            <?php elseif ($post["status"]  == "c"): ?>
                                <span class="text-red-600 font-bold text-sm">Supprimé</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5"><?= getNbComments($post["id"])["nbComments"] ?></td>
                        <td class="px-6 py-5 flex flex-row gap-x-3 *:text-sm">
                            <?php if ($post["isDeleted"]): ?>
                                <button disabled title="Désactivé"><i class="fa-solid fa-eye-slash text-gray-200"></i></button>
                            <?php else: ?>
                                <?php if($post["isActive"]): ?>
                                    <button><i  class="fa-solid fa-eye text-green-500"></i></button>
                                <?php else: ?>
                                    <button><i  class="fa-solid fa-eye-slash text-orange-400"></i></button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <button><i title="Modifier" data-row-update="<?= $post["reference"]?>" class="fa-solid fa-pen-to-square text-gray-600"></i></button>

                            <?php if(!$post["isDeleted"]): ?>
                                <button data-modal-target="modalRestaure" data-modal-hide="modalRestore"  value="<?= $post["id"]?>"><i title="Supprimé" class="fa-solid fa-trash text-red-600"></i></button>
                            <?php else: ?>
                                <button value="<?= $post["id"]?>"><i title="Restaurer" class="fa-solid fa-trash-restore text-green-500"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center italic text-gray-500 py-5">Vous n'avez encore posté aucun poste.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    // set the modal menu element
    const $modalRestaure = document.getElementById('modalRestore');

    // options with default values
    const options = {
        placement: 'center',
        backdrop: 'dynamic',
        backdropClasses:
            'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
        closable: true,
        onHide: () => {
            console.log('modal is hidden');
        },
        onShow: () => {
            console.log('modal is shown');
        },
        onToggle: () => {
            console.log('modal has been toggled');
        },
    };

    // instance options object
    const instanceOptions = {
        id: 'modalRestore',
        override: true
    };

    const modal = new $modalRestaure($modalRestaure, options, instanceOptions);
</script>




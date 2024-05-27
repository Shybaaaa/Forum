<?php
require_once "../../../private/functions/functions.php";

$logs = getLogs();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST) {
        case isset($_POST["search"]):
            $search = $_POST["inputSearch"];
            $logs = searchLogs($_POST["inputSearch"]);
            break;
    }
}

?>

<div class="w-10/12 h-[80%] bg-white px-3.5 rounded-lg py-2.5 dark:bg-slate-700">
    <div class="overflow-x-auto h-full flex flex-col justify-between">
        <div>
            <form action="" method="POST">

                <div class="relative mb-3 mr-5 float-left">
                    <label for="inputSearch" class="sr-only">Rechercher</label>
                    <input id="inputSearch" name="inputSearch" type="text" placeholder="Recherche..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400" />
                    <button type="submit" name="search">
                        <i class="fa-solid fa-search text-gray-700 absolute top-3 left-3 dark:text-slate-400"></i>
                    </button>
                </div>
            </form>
            <table class="min-w-full text-left text-xs whitespace-nowrap">
                <thead class="uppercase tracking-wider border-b-2 dark:text-slate-300">
                    <tr>
                        <th scope="col" class="px-6 py-5">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Types
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Logs
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log) : ?>
                        <tr class="border-b dark:text-slate-300">
                            <th scope="row" class="px-6 py-5"><?= $log["id"] ?></th>
                            <td class="px-6 py-5"><?= $log["type"] ?></td>
                            <td class="px-6 py-5"><?= $log["logs"] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<?php

//Système de notification
function newNotification(string $type, string $message, bool $isClosable, string $icon)
{
    $message = htmlspecialchars(trim($message));

    $notification = [
        "type" => $type,
        "message" => $message,
        "isClosable" => $isClosable,
        "icon" => $icon,
    ];

    setcookie("notification", json_encode($notification), time() + 60, "/");
}

function renderNotification()
{
    if (isset($_COOKIE["notification"])) {
        $notif = json_decode($_COOKIE["notification"], true);
        $type = $notif["type"];
        $message = $notif["message"];
        $isClosable = $notif["isClosable"];
        $icon = $notif["icon"];

        switch ($type) {
            case "success":
                $color = "bg-green-100 text-green-500";
                break;
            case "error":
                $color = "bg-red-100 text-red-500";
                break;
            case "warning":
                $color = "bg-yellow-100 text-yellow-500";
                break;
            case "info":
                $color = "bg-blue-100 text-blue-500";
                break;
            default:
                $color = "bg-gray-100 text-gray-500";
                break;
        }


        return '
        <div id="toast-success" class="fixed top-20 right-10 flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ' . $color . ' rounded-lg">
                <i class="fa-solid ' . $icon . ' fill-current" aria-hidden="true"></i>
            </div>
            <div class="ms-3 text-sm font-normal">' . $message . '</div>
            <button type="button" id="closeNotification" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-success" aria-label="Close">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7
                    7l-6 6" />
                </svg>
            </button>
        </div>';
    }
}


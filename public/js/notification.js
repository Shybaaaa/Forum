const btnCloseNotification = document.getElementById('closeNotification');

btnCloseNotification.addEventListener('click', () => {
    document.cookie = "notification=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.getElementById('notification').classList.add('hidden');
});
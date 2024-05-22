window.addEventListener("load", function () {
    const loader = document.getElementById("loader");
    setTimeout(() => {
        loader.style.opacity = 0;
        setTimeout(() => {
            loader.classList.add("hidden");
        }, 500);
    }, 500);
});
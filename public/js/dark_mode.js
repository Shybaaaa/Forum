const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
const themeToggleBtn = document.getElementById('theme-toggle');

const themeToggleDarkIconSide = document.getElementById('theme-toggle-dark-icon-side');
const themeToggleLightIconSide = document.getElementById('theme-toggle-light-icon-side');
const themeToggleBtnSide = document.getElementById('theme-toggle-side');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleLightIcon.classList.remove('hidden');
    if (themeToggleLightIconSide !== null) {
        themeToggleLightIconSide.classList.remove('hidden');
    }
    document.documentElement.classList.add('dark');
    localStorage.setItem('color-theme', 'dark');
} else {
    themeToggleDarkIcon.classList.remove('hidden');
    if (themeToggleDarkIconSide !== null) {
        themeToggleDarkIconSide.classList.remove('hidden');
    }
    document.documentElement.classList.remove('dark');
    localStorage.setItem('color-theme', 'light');
}

// Dark mode side bar


themeToggleBtn.addEventListener('click', function() {
    // toggle icons inside button
    themeToggleDarkIcon.classList.toggle('hidden');
    themeToggleLightIcon.classList.toggle('hidden');

    // if set via local storage previously
    if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }

    // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    }
    
});

themeToggleBtnSide.addEventListener('click', function() {
    // toggle icons inside button
    themeToggleDarkIconSide.classList.toggle('hidden');
    themeToggleLightIconSide.classList.toggle('hidden');

    // if set via local storage previously
    if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }

        // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    }
});


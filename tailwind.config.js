/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./**/*.{php,css,js}",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('flowbite/plugin')
    ],
    darkMode: ['selector', '[data-mode="dark"]'],
    

}



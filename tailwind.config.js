/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./**/*.{php,css,js}",
        "./node_modules/flowbite/**/*.js",
        "./**/*.{js,ts,jsx,tsx,mdx}",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('flowbite/plugin')
    ],

    important: true,
    darkMode:'class',
}



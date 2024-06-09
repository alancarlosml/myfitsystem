import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./node_modules/flowbite/**/*.js"
    ],
    theme: {
      extend: {
        colors : {
            'primary' : '#f97316',
            'secondary' : '#64748b',
            'dark' : '#0f172a',
        },
        fontFamily: {
            sans: ['Roboto', ...defaultTheme.fontFamily.sans],
        },
      },
    },
    plugins: [
        forms,
        require('flowbite/plugin')
    ],
    darkMode: 'class',
}
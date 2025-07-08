import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                maroon: {
                    400: '#f98080',
                    900: '#771d1d',
                },
                clsu: {
                    maroon: '#800000',
                    green: '#006400',
                },
            },
        },
    },

    plugins: [
        forms,
    ],
};

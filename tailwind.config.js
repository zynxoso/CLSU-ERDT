import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
                secondary: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                },
                maroon: {
                    50: '#fdf2f2',
                    100: '#fce8e8',
                    200: '#fad5d5',
                    300: '#f8b4b4',
                    400: '#f98080',
                    500: '#f05252',
                    600: '#e02424',
                    700: '#c81e1e',
                    800: '#9b1c1c',
                    900: '#771d1d',
                    950: '#450a0a',
                },
                clsu: {
                    maroon: '#800000',
                    green: '#006400',
                },
            },
            fontFamily: {
                sans: ['Inter var', 'sans-serif'],
            },
            spacing: {
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
            },
        },
    },

    plugins: [
        forms,
    ],
};

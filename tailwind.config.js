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
                // Primary brand colors
                primary: {
                    50: '#E8F5E9',
                    100: '#C8E6C9',
                    200: '#A5D6A7',
                    300: '#81C784',
                    400: '#66BB6A',
                    500: '#4CAF50', // Main green
                    600: '#43A047',
                    700: '#388E3C',
                    800: '#2E7D32',
                    900: '#1B5E20',
                },
                // Secondary colors
                secondary: {
                    50: '#E3F2FD',
                    100: '#BBDEFB',
                    200: '#90CAF9',
                    300: '#64B5F6',
                    400: '#42A5F5',
                    500: '#4A90E2', // Main blue
                    600: '#357ABD',
                    700: '#1976D2',
                    800: '#1565C0',
                    900: '#0D47A1',
                },
                // Warning colors
                warning: {
                    50: '#FFF8E1',
                    100: '#FFECB3',
                    200: '#FFE082',
                    300: '#FFD54F',
                    400: '#FFCC02',
                    500: '#FFCA28', // Main yellow
                    600: '#FFB300',
                    700: '#FF8F00',
                    800: '#F57C00',
                    900: '#E65100',
                },
                // Error colors
                error: {
                    50: '#FFEBEE',
                    100: '#FFCDD2',
                    200: '#EF9A9A',
                    300: '#E57373',
                    400: '#EF5350',
                    500: '#EF4444', // Main red
                    600: '#E53935',
                    700: '#D32F2F',
                    800: '#C62828',
                    900: '#B71C1C',
                },
                // Success colors (alias for primary)
                success: {
                    50: '#E8F5E9',
                    100: '#C8E6C9',
                    200: '#A5D6A7',
                    300: '#81C784',
                    400: '#66BB6A',
                    500: '#4CAF50',
                    600: '#43A047',
                    700: '#388E3C',
                    800: '#2E7D32',
                    900: '#1B5E20',
                },
                // Info colors (alias for secondary)
                info: {
                    50: '#E3F2FD',
                    100: '#BBDEFB',
                    200: '#90CAF9',
                    300: '#64B5F6',
                    400: '#42A5F5',
                    500: '#4A90E2',
                    600: '#357ABD',
                    700: '#1976D2',
                    800: '#1565C0',
                    900: '#0D47A1',
                },
                // CLSU brand colors
                clsu: {
                    maroon: '#800000',
                    green: '#006400',
                },
                // Legacy maroon colors (for backward compatibility)
                maroon: {
                    50: '#FFF5F5',
                    100: '#FED7D7',
                    200: '#FEB2B2',
                    300: '#FC8181',
                    400: '#F98080',
                    500: '#F56565',
                    600: '#E53E3E',
                    700: '#C53030',
                    800: '#9B2C2C',
                    900: '#771D1D',
                },
                // Neutral grays
                neutral: {
                    50: '#FAFAFA',
                    100: '#F5F5F5',
                    200: '#EEEEEE',
                    300: '#E0E0E0',
                    400: '#BDBDBD',
                    500: '#9E9E9E',
                    600: '#757575',
                    700: '#616161',
                    800: '#424242',
                    900: '#212121',
                },
                // Purple for special cases
                purple: {
                    50: '#F3E5F5',
                    100: '#E1BEE7',
                    200: '#CE93D8',
                    300: '#BA68C8',
                    400: '#AB47BC',
                    500: '#9C27B0',
                    600: '#8E24AA',
                    700: '#7B1FA2',
                    800: '#6A1B9A',
                    900: '#4A148C',
                },
            },
            fontFamily: {
                'sans': ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                'display': ['Montserrat', 'Inter', 'system-ui', 'sans-serif'],
                'body': ['Inter', 'system-ui', 'sans-serif'],
                'pdf': ['DejaVu Sans', 'sans-serif'],
            },
        },
    },

    plugins: [
        forms,
    ],
};

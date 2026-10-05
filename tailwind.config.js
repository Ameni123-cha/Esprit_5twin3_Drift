import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f1f8f4',
                    100: '#dcefe3',
                    200: '#b8ddc5',
                    300: '#86c49c',
                    400: '#4fa56f',
                    500: '#2f7a4c',
                    600: '#24633c',
                    700: '#1b4d2f',
                    800: '#143a24',
                    900: '#0f2c1b',
                },
            },
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            boxShadow: {
                soft: '0 10px 30px -12px rgba(20, 58, 36, 0.18)',
            },
        },
    },

    plugins: [forms],
};

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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'kitchen-green': {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                'kitchen-gold': {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                },
                'kitchen-red': {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                },
                'kitchen-cream': '#fdfbf7',
            },
        },
    },

    plugins: [forms],
};

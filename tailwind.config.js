const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                chocolate: {
                    '50': '#faf5e9',
                    '100': '#fbedcc',
                    '200': '#f8df99',
                    '300': '#f5c756',
                    '400': '#f2a421',
                    '500': '#f17e0e',
                    '600': '#ef5c09',
                    '700': '#c6430e',
                    '800': '#a33515',
                    '900': '#862c16',
                },
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ],
};

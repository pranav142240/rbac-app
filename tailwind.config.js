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
                primary: {
                    50: 'rgb(238 242 255)',
                    100: 'rgb(224 231 255)',
                    200: 'rgb(199 210 254)',
                    300: 'rgb(165 180 252)',
                    400: 'rgb(129 140 248)',
                    500: 'rgb(99 102 241)',
                    600: 'rgb(79 70 229)',
                    700: 'rgb(67 56 202)',
                    800: 'rgb(55 48 163)',
                    900: 'rgb(49 46 129)',
                },
                success: {
                    50: 'rgb(236 253 245)',
                    100: 'rgb(209 250 229)',
                    200: 'rgb(167 243 208)',
                    300: 'rgb(110 231 183)',
                    400: 'rgb(52 211 153)',
                    500: 'rgb(16 185 129)',
                    600: 'rgb(5 150 105)',
                    700: 'rgb(4 120 87)',
                    800: 'rgb(6 95 70)',
                    900: 'rgb(6 78 59)',
                },
                warning: {
                    50: 'rgb(255 251 235)',
                    100: 'rgb(254 243 199)',
                    200: 'rgb(253 230 138)',
                    300: 'rgb(252 211 77)',
                    400: 'rgb(251 191 36)',
                    500: 'rgb(245 158 11)',
                    600: 'rgb(217 119 6)',
                    700: 'rgb(180 83 9)',
                    800: 'rgb(146 64 14)',
                    900: 'rgb(120 53 15)',
                },
                error: {
                    50: 'rgb(255 241 242)',
                    100: 'rgb(255 228 230)',
                    200: 'rgb(254 205 211)',
                    300: 'rgb(253 164 175)',
                    400: 'rgb(251 113 133)',
                    500: 'rgb(244 63 94)',
                    600: 'rgb(225 29 72)',
                    700: 'rgb(190 18 60)',
                    800: 'rgb(159 18 57)',
                    900: 'rgb(136 19 55)',
                },
                info: {
                    50: 'rgb(240 249 255)',
                    100: 'rgb(224 242 254)',
                    200: 'rgb(186 230 253)',
                    300: 'rgb(125 211 252)',
                    400: 'rgb(56 189 248)',
                    500: 'rgb(14 165 233)',
                    600: 'rgb(2 132 199)',
                    700: 'rgb(3 105 161)',
                    800: 'rgb(7 89 133)',
                    900: 'rgb(12 74 110)',
                },
            },
        },
    },

    plugins: [forms],
};

const colors = require('tailwindcss/colors')

module.exports = {
  purge: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
        margin: {
            '-2': '-0.5rem',
        },
        colors: {
            tomato: {
                '50':  '#fbf7f2',
                '100': '#fcefe4',
                '200': '#f9dac5',
                '300': '#f7bc92',
                '400': '#f78e53',
                '500': '#f7642c',
                '600': '#ed411c',
                '700': '#ce311e',
                '800': '#a52721',
                '900': '#84211e',
            },
        }
    },
  },
  variants: {
    extend: {
        rotate: ['group-hover'],
    },
  },
  plugins: [
      require('@tailwindcss/forms'),
  ],
}

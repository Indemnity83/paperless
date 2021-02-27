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
        fontFamily: {
            'brand': ['RocknRoll One', 'sans-serif'],
        },
        margin: {
            '-1': '-0.25rem',
            '-2': '-0.5rem',
        },
        colors: {
            chocolate: {
                '50':  '#faf5e9',
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

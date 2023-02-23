/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '*.php',
    'templates/**/*.{php,twig}',
    './Components/**/*.{php,twig}'
  ],
  theme: {
    colors: {
      white: '#fff',
      black: '#000',
      green: '#169b83',
      grey: '#7b838d',
      current: 'currentColor'
    },
    screens: {
      sm: '641px',
      md: '769px',
      lg: '1181px',
      xl: '1281px',
      max: '1440px'
    },
    extend: {
      borderWidth: {
        DEFAULT: '1px',
        0: '0',
        2: '2px',
        3: '3px',
        4: '4px'
      },
      spacing: {
        sm: '15px',
        md: '35px',
        lg: '50px'
      }
    }
  },
  plugins: []
}

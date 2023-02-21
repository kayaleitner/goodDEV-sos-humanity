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
      blue: '#1113ff',
      current: 'currentColor'
    },
    screens: {
      sm: '641px',
      md: '769px',
      lg: '1181px',
      xl: '1281px'
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
        lg: '35px'
      }
    }
  },
  plugins: []
}

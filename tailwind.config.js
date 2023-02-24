/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '*.php',
    'templates/**/*.{php,twig}',
    './Components/**/*.{php,twig}'
  ],
  theme: {
    borderWidth: {
      DEFAULT: '1px',
      0: '0',
      2: '2px',
      3: '3px'
    },
    colors: {
      white: '#fff',
      black: '#000',
      navygreen: '#169b83',
      blue: '#272a5f',
      green: '#52b756',
      grey: '#7b838d',
      current: 'currentColor'
    },
    fontSize: {
      btn: ['1.25rem', {
        lineHeight: '1.25rem',
        fontWeight: '400'
      }],
      h1: ['4.813rem', {
        lineHeight: '5.25rem',
        // letterSpacing: '-0.01em',
        fontWeight: '400'
      }],
      h2: ['3.438rem', {
        lineHeight: '2.25rem',
        letterSpacing: '-0.02em',
        fontWeight: '700'
      }],
      h3: ['1.875rem', {
        lineHeight: '2.25rem',
        letterSpacing: '-0.02em',
        fontWeight: '700'
      }],
      h4: ['2rem', {
        lineHeight: 'auto',
        fontWeight: '700'
      }],
      h5: ['1.563rem', {
        lineHeight: 'auto',
        fontWeight: '400'
      }],
      h6: ['1.25rem', {
        lineHeight: 'auto',
        fontWeight: '400'
      }]
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
        xs: '10px',
        sm: '15px',
        md: '35px',
        lg: '50px',
        xl: '75px',
        xxl: '100px'
      }
    }
  },
  plugins: []
}

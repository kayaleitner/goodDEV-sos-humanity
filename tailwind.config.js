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
      cbegreen: '#169b83',
      blue: '#272a5f',
      green: '#52b756',
      grey: '#7b838d',
      current: 'currentColor',
      transparent: 'transparent'
    },
    fontSize: {
      small: ['0.938rem', {
        lineHeight: 'normal',
        fontWeight: '400'
      }],
      base: ['1rem', {
        lineHeight: 'normal',
        fontWeight: '400'
      }],
      large: ['1.25rem', {
        lineHeight: 'normal',
        fontWeight: '400'
      }],
      button: ['1.125rem', {
        lineHeight: '1.125rem',
        fontWeight: '400'
      }],
      h1: ['4.813rem', {
        lineHeight: 'normal',
        fontWeight: '400'
      }],
      h2: ['3.438rem', {
        lineHeight: '110%',
        fontWeight: '700'
      }],
      h3: ['2.188rem', {
        lineHeight: 'normal',
        fontWeight: '400'
      }],
      h4: ['2rem', {
        lineHeight: 'normal',
        fontWeight: '700'
      }],
      h5: ['1.563rem', {
        lineHeight: '140%',
        fontWeight: '400'
      }],
      h6: ['1.563rem', {
        lineHeight: 'normal',
        fontWeight: '400'
      }]
    },
    screens: {
      sm: '640px',
      md: '780px',
      lg: '1280px',
      xl: '1440px',
      max: '1600px'
    },
    extend: {
      aspectRatio: {
        '4/3': '4 / 3',
        '3/4': '3 / 4'
      },
      borderWidth: {
        DEFAULT: '1px',
        0: '0',
        2: '2px',
        3: '3px',
        4: '4px'
      },
      height: {
        xs: '10px',
        sm: '15px',
        md: '35px',
        lg: '50px',
        xl: '75px',
        xxl: '125px',
        max: '200px'
      },
      spacing: {
        xs: '10px',
        sm: '15px',
        md: '35px',
        lg: '50px',
        xl: '75px',
        xxl: '120px',
        max: '200px'
      }
    }
  },
  plugins: []
}

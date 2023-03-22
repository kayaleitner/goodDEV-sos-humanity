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
      green: '#6FF79D',
      lightgreen: '#7CD3B9',
      lightgrey: '#D1D5DB',
      grey: '#7b838d',
      current: 'currentColor',
      transparent: 'transparent'
    },
    fontSize: {
      body: ['1.563rem', {
        lineHeight: '140%'
      }],
      bodyMobile: ['1.25rem', {
        lineHeight: 'normal'
      }],
      button: ['1.125rem', {
        lineHeight: '1.125rem'
      }],
      titleLarge: ['4.813rem', {
        lineHeight: 'normal'
      }],
      titleLargeMobile: ['2.813rem', {
        lineHeight: 'normal'
      }],
      titleSmall: ['3.438rem', {
        lineHeight: '110%'
      }],
      titleSmallMobile: ['2.188rem', {
        lineHeight: 'normal'
      }],
      subtitle: ['2.188rem', {
        lineHeight: 'normal'
      }],
      subtitleMobile: ['1.875rem', {
        lineHeight: 'normal'
      }],
      glance: ['2.813rem', {
        lineHeight: '110%'
      }],
      glanceMobile: ['1.5rem', {
        lineHeight: '110%'
      }],
      caption: ['1.125rem', {
        lineHeight: 'normal'
      }],
      captionMobile: ['0.938rem', {
        lineHeight: 'normal'
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
        '3/4': '3 / 4',
        '2/1': '2 / 1'
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
        sm: '20px',
        md: '30px',
        lg: '50px',
        xl: '75px',
        xxl: '120px',
        max: '200px'
      },
      typography: {
        DEFAULT: {
          css: {
            color: 'currentColor',
            fontSize: '16px'
            // a: {
            //   color: '#currentColor',
            //   '&:hover': {
            //     color: '#currentColor',
            //   },
            // },
          }
        }
      }
    }
  },
  plugins: [
    require('@tailwindcss/typography')
  ]
}

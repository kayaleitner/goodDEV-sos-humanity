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
      body: ['1.563rem', '140%'],
      bodyMobile: ['1.25rem', 'normal'],
      button: ['1.25rem', 'normal'],
      titleLarge: ['4.813rem', 'normal'],
      titleLargeMobile: ['2.813rem', 'normal'],
      titleSmall: ['3.438rem', '110%'],
      titleSmallMobile: ['2.188rem', 'normal'],
      subtitle: ['2.188rem', 'normal'],
      subtitleMobile: ['1.875rem', 'normal'],
      glance: ['2.813rem', '110%'],
      glanceMobile: ['1.5rem', '110%'],
      caption: ['1.125rem', 'normal'],
      captionMobile: ['0.938rem', 'normal']
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
      prose: {
        xxl: '25px'
      },
      spacing: {
        xs: '10px',
        sm: '20px',
        md: '35px',
        lg: '50px',
        xl: '75px',
        xxl: '120px',
        max: '200px'
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: 'currentColor',
            fontSize: '1.25rem',
            lineHeight: 'normal',
            // '@media (min-width: 1280px)': {
            //   fontSize: '1.563rem'
            // }
            a: {
              color: 'currentColor',
              '&:hover': {
                color: 'currentColor'
              }
            },
            hr: {
              marginBottom: '1.3em',
              marginTop: '0'
            }
          }
        },
        lg: {
          css: {
            color: 'currentColor',
            fontSize: '1.563rem',
            lineHeight: '140%',
            // '@media (min-width: 1280px)': {
            //   fontSize: '1.563rem'
            // }
            hr: {
              marginBottom: '1.3em',
              marginTop: '0'
            }
          }
        }
      })
    }
  },
  plugins: [
    require('@tailwindcss/typography')
  ]
}

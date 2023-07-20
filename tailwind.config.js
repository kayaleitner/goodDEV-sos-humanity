/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '*.php',
    'templates/**/*.{php,twig}',
    './Components/**/*.{php,twig}',
    './Atoms/**/*.{php,twig}'
  ],
  theme: {
    borderWidth: {
      DEFAULT: '2px',
      0: '0',
      1: '1px',
      2: '2px',
      3: '3px'
    },
    colors: {
      /** Colors (Primitives) */
      white: '#fff',
      black: '#000',
      current: 'currentColor',
      transparent: 'transparent',
      cbegreen: '#169b83',
      blue: '#0c2569',
      green: '#6ff79d',
      lightgreen: '#7cd3b9',
      mint: '#59d6b8',
      lightgrey: '#d1d5db',
      grey: '#7b838d',
      /** Colors (Tokens) */
      defaultTextColor: '#000',
      defaultBgColor: '#fff',
      brandColor: '#169b83',
      accentColor: '#6ff79d',
      hoverColor: '#59d6b8',
      borderColor: '#d1d5db',
      uiColor: '#7b838d',
      errorColor: '#f05252',
      dangerColor: '#ffb900',
    },
    fontSize: {
      body: ['1.563rem', '140%'],
      bodyMobile: ['1.25rem', 'normal'],
      button: ['1.25rem', 'normal'],
      buttonSmall: ['1.125rem', 'normal'],
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
      xs: '640px',
      sm: '780px',
      md: '1024px',
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
        DEFAULT: '2px',
        0: '0',
        1: '1px',
        2: '2px',
        3: '3px',
        4: '4px'
      },
      brightness: {
        70: '.70'
      },
      fontFamily: {
        mono: ['Aeonik Mono']
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
        sm: '18px',
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
            a: {
              color: 'currentColor',
              '&:hover': {
                color: 'currentColor'
              }
            },
            strong: {
              color: 'currentColor',
              '&:hover': {
                color: 'currentColor'
              }
            },
            hr: {
              borderColor: 'currentColor',
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
            hr: {
              borderColor: 'currentColor',
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

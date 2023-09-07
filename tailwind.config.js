/** @type {import('tailwindcss').Config} */
export const content = [
  '*.php',
  'templates/**/*.{php,twig}',
  './Components/**/*.{php,twig}',
  './Atoms/**/*.{php,twig}',
]
export const theme = {
  borderWidth: {
    DEFAULT: '2px',
    0: '0',
    1: '1px',
    2: '2px',
    3: '3px',
  },
  colors: {
    /** Colors (Primitives) */
    current: 'currentColor',
    transparent: 'transparent',
    /** Colors (Tokens) */
    textColor: 'var(--textColor)',
    bgColor: 'var(--bgColor)',
    brandColor: 'var(--brandColor)',
    hoverColor: 'var(--hoverColor)',
    // accentColor: 'var(--accentColor)',
    // borderColor: 'var(--borderColor)',
    // uiColor: 'var(--uiColor)',
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
    captionMobile: ['0.938rem', 'normal'],
  },
  screens: {
    xs: '640px',
    sm: '780px',
    md: '980px',
    lg: '1080px',
    xl: '1280px',
    max: '1440px',
  },
  extend: {
    aspectRatio: {
      '4/3': '4 / 3',
      '3/4': '3 / 4',
      '2/1': '2 / 1',
    },
    borderWidth: {
      DEFAULT: '2px',
      0: '0',
      1: '1px',
      2: '2px',
      3: '3px',
      4: '4px',
    },
    brightness: {
      70: '.70',
    },
    fontFamily: {
      mono: ['Aeonik Mono'],
    },
    height: {
      xs: '10px',
      sm: '15px',
      md: '35px',
      lg: '50px',
      xl: '75px',
      xxl: '125px',
      max: '200px',
    },
    spacing: {
      xs: '10px',
      sm: '18px',
      md: '35px',
      lg: '50px',
      xl: '75px',
      xxl: '120px',
      max: '200px',
    },
  },
}
export const purge = {
  safelist: ['w-1/2', 'translate-x-full', '!translate-x-0'],
}

export const plugins = []

export const darkMode = 'class'

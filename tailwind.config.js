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
    tangerine: 'var(--tangerine)',
    yellow: 'var(--yellow)',
    /** Colors (Tokens) */
    textColor: 'var(--textColor)',
    bgColor: 'var(--bgColor)',
    brandColor: 'var(--brandColor)',
    accentColor: 'var(--accentColor)',
    hoverColor: 'var(--hoverColor)',
    activeColor: 'var(--bluepressed)',
    borderColor: 'var(--borderColor)',
    errorColor: 'var(--errorColor)',
    successColor: 'var(--successColor)',
    uiColor: 'var(--uiColor)',
  },
  screens: {
    xs: '640px',
    sm: '780px',
    md: '980px',
    lg: '1080px',
    xl: '1281px',
    xxl: '1441px',
    wide: { 'raw': '(min-width: 1600px) and (min-height: 800px)' },
    // max: { 'raw': '(min-width: 1880px) and (min-height: 1000px)' },
    max: '1880px',
  },
  container: {
    padding: {
      DEFAULT: '20px',
      xs: '60px',
    },
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
      sm: '20px',
      md: '40px',
      lg: '60px',
      xl: '80px',
      xxl: '120px',
      max: '200px',
    },
  },
}
export const safelist = [
  'w-1/2',
  'translate-x-full',
  '!translate-x-0',
  'after-marker',
  'after-marker--dark',
  'bg-tangerine', // used in tinyMCE
  'bg-yellow', // used in tinyMCE
  {
    pattern: /col-span-\d/,
    variants: ['xs', 'sm', 'md', 'lg', 'xl', 'xxl', 'wide', 'max'],
  },
  {
    pattern: /col-start-\d/,
    variants: ['xs', 'sm', 'md', 'lg', 'xl', 'xxl', 'wide', 'max'],
  },
]

export const plugins = []

export const darkMode = 'class'

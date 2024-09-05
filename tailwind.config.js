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
    white: 'var(--white)',

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
    min: '375px',
    xs: '680px',
    sm: '780px',
    md: '1180px',
    lg: '1280px',
    xl: '1440px',
    max: '1600px',
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
      sans: ['Inter', 'Roboto', 'Open Sans', 'Helvetica', 'Arial', 'Sans'],
      serif: ['EB Garamond', 'Georgia', 'Times New Roman', 'Serif'],
      mono: ['IBM Plex Mono', 'Menlo', 'courier', 'monospace'],
    },
    height: {
      min: '5px',
      xs: '10px',
      sm: '20px',
      md: '40px',
      lg: '60px',
      xl: '80px',
      max: '120px',
      extra: '200px',
    },
    spacing: {
      min: '5px',
      xs: '10px',
      sm: '20px',
      p: '30px',
      md: '40px',
      lg: '60px',
      xl: '80px',
      max: '120px',
      extra: '200px',
      navBar: 'var(--navBarHeight)',
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
    variants: ['xs', 'sm', 'md', 'lg', 'xl', 'max'],
  },
  {
    pattern: /col-start-\d/,
    variants: ['xs', 'sm', 'md', 'lg', 'xl', 'max'],
  },
  {
    pattern: /col-end-\d/,
    variants: ['xs', 'sm', 'md', 'lg', 'xl', 'max'],
  },
]

export const plugins = []

export const darkMode = 'class'

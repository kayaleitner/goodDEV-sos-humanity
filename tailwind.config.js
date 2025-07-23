/** @type {import('tailwindcss').Config} */
export const content = [
  '*.php',
  'templates/**/*.{php,twig}',
  './Components/**/*.{php,twig}',
  './Atoms/**/*.{php,twig}',
]
export const theme = {
  colors: {
    /** Colors (Primitives) */
    current: 'currentColor',
    transparent: 'transparent',
    tangerine: 'var(--tangerine)',
    yellow: 'var(--yellow)',
    white: 'var(--white)',
    blue: 'var(--blue)',

    /** Colors (Tokens) */
    brandColor: 'var(--brandColor)',
    accentColor: 'var(--accentColor)',

    textColor: 'var(--textColor)',
    linkColor: 'var(--linkColor)',
    hoverColor: 'var(--hoverColor)',
    activeColor: 'var(--bluepressed)',

    bgColor: 'var(--bgColor)',
    paperColor: 'var(--paperColor)',
    borderColor: 'var(--borderColor)',
    uiColor: 'var(--grey)',

    errorColor: 'var(--errorColor)',
    successColor: 'var(--successColor)',
  },
  fontFamily: {
    sans: ['Source Sans Pro', 'Helvetica', 'Arial', 'sans-serif'],
    serif: ['EB Garamond', 'Georgia', 'Times New Roman', 'serif'],
    mono: ['Ubuntu Mono', 'IBM Plex Mono', 'Menlo', 'Courier', 'monospace'],
    display: ['Source Sans Pro', 'Helvetica', 'Arial', 'sans-serif'],
  },  
  borderRadius: {
    DEFAULT: '2rem',
  },
  borderWidth: {
    DEFAULT: '.0625rem',
    0: '0',
  },
  screens: {
    min: '354px',     // Keeping this as is
    xs: '540px',      // Matches $breakpoint-mobile
    sm: '768px',      // Updated from 780px to match $breakpoint-tablet
    md: '1024px',     // Updated from 1180px to match $breakpoint-desktop
    lg: '1280px',     // Keeping this as is
    xl: '1440px',     // Matches $breakpoint-desktop-large
    max: '1920px',    // Updated from 1600px to match $breakpoint-desktop-xlarge
  },  
  extend: {
     colors: {
    /** Colors (Primitives) */
    current: 'currentColor',
    transparent: 'transparent',
    tangerine: 'var(--tangerine)',
    yellow: 'var(--yellow)',
    white: 'var(--white)',
    blue: 'var(--blue)',

    /** Colors (Tokens) */
    brandColor: 'var(--brandColor)',
    accentColor: 'var(--accentColor)',

    textColor: 'var(--textColor)',
    linkColor: 'var(--linkColor)',
    hoverColor: 'var(--hoverColor)',
    activeColor: 'var(--bluepressed)',

    bgColor: 'var(--bgColor)',
    paperColor: 'var(--paperColor)',
    borderColor: 'var(--borderColor)',
    uiColor: 'var(--grey)',

    errorColor: 'var(--errorColor)',
    successColor: 'var(--successColor)',
  },
    aspectRatio: {
      '4/3': '4 / 3',
      '3/4': '3 / 4',
      '2/1': '2 / 1',
    },
    brightness: {
      70: '.70',
    },
    height: {
      xs: '5px',
      sm: '10px',
      md: '20px',
      lg: '30px',
      xl: '60px',
      xxl: '100px',
      max: '120px',
      extra: '250px',
      xxsmall: 'var(--padding_xsmall)',
      xsmall: 'var(--padding_xsmall)',
      small: 'var(--padding_small)',
      medium: 'var(--padding_medium)',
      large: 'var(--padding_large)',
      xlarge: 'var(--padding_xlarge)',
      xxlarge: 'var(--padding_xxlarge)',
    },
    width: {
      xs: '5px',
      sm: '10px',
      md: '20px',
      lg: '30px',
      xl: '60px',
      xxl: '100px',
      max: '120px',
      extra: '250px',
      xxsmall: 'var(--padding_xsmall)',
      xsmall: 'var(--padding_xsmall)',
      small: 'var(--padding_small)',
      medium: 'var(--padding_medium)',
      large: 'var(--padding_large)',
      xlarge: 'var(--padding_xlarge)',
      xxlarge: 'var(--padding_xxlarge)',
    },
    spacing: { 
      xs: '5px',
      sm: '10px',
      md: '20px',
      lg: '30px',
      xl: '60px',
      xxl: '100px',
      max: '120px',
      extra: '250px', 
      xxsmall: 'var(--padding_xsmall)',
      xsmall: 'var(--padding_xsmall)',
      small: 'var(--padding_small)',
      medium: 'var(--padding_medium)',
      large: 'var(--padding_large)',
      xlarge: 'var(--padding_xlarge)',
      xxlarge: 'var(--padding_xxlarge)',
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
  'gap-x-md',
  'gap-y-sm',
  '!gap-y-sm',
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

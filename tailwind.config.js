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

    bgColor: 'var(--bgColor)',
    paperColor: 'var(--paperColor)',

    errorColor: 'var(--errorColor)',
    successColor: 'var(--successColor)',
  },
  fontFamily: {
    sans: ['neue-haas-grotesk-text', 'Inter', 'Roboto', 'Open Sans', 'Helvetica', 'Arial', 'Sans'],
    serif: ['EB Garamond', 'Georgia', 'Times New Roman', 'Serif'],
    mono: ['IBM Plex Mono', 'Menlo', 'courier', 'monospace'],
    display: ['degular-display', 'Inter', 'Roboto', 'Open Sans', 'Helvetica', 'Arial', 'Sans'],
  },
  borderRadius: {
    DEFAULT: '2rem',
  },
  borderWidth: {
    DEFAULT: '.0625rem',
    0: '0',
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
  extend: {
    aspectRatio: {
      '4/3': '4 / 3',
      '3/4': '3 / 4',
      '2/1': '2 / 1',
    },
    brightness: {
      70: '.70',
    },
    height: {
      min: '.125rem',
      xs: '.5rem',
      sm: '1rem',
      md: '2rem',
      lg: '3rem',
      xl: '4rem',
      max: '6rem',
      extra: '12rem',
    },
    width: {
      min: '.125rem',
      xs: '.5rem',
      sm: '1rem',
      md: '2rem',
      lg: '3rem',
      xl: '4rem',
      max: '6rem',
      extra: '12rem',
    },
    spacing: {
      min: '.125rem',
      xs: '.5rem',
      sm: '1rem',
      md: '2rem',
      lg: '3rem',
      xl: '4rem',
      max: '6rem',
      extra: '12rem',
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

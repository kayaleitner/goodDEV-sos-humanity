import 'vite/modulepreload-polyfill'
import './scripts/loadCustomElements'
import './scripts/custom'
import './scripts/scroll'
import Alpine from 'alpinejs'
import FlyntComponent from './scripts/FlyntComponent'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import 'lazysizes'

if (import.meta.env.DEV) {
  import('@vite/client')
}

window.customElements.define(
  'flynt-component',
  FlyntComponent
)

window.Alpine = Alpine
Alpine.start()

gsap.registerPlugin(ScrollTrigger)

import.meta.glob([
  '../Components/**',
  '../assets/**',
  '!**/*.js',
  '!**/*.scss',
  '!**/*.php',
  '!**/*.twig',
  '!**/screenshot.png',
  '!**/*.md'
])

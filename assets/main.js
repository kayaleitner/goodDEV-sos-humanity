import 'vite/modulepreload-polyfill'
import './scripts/loadCustomElements'
import './scripts/custom'
import Alpine from 'alpinejs'
import FlyntComponent from './scripts/FlyntComponent'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import * as eva from 'eva-icons'
import 'lazysizes'

global.eva = eva

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

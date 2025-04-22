import 'vite/modulepreload-polyfill'
import './scripts/custom'
import './scripts/parallax'
import Alpine from 'alpinejs'
import FlyntComponent from './scripts/FlyntComponent'
import lazySizes from 'lazysizes'

lazySizes.cfg.preloadAfterLoad = true

if (import.meta.env.DEV) {
  import('@vite/client')
}

import.meta.glob([
  '../Components/**',
  '../assets/**',
  '!**/*.js',
  '!**/*.css',
  '!**/*.php',
  '!**/*.twig',
  '!**/screenshot.png',
  '!**/*.md',
])

window.customElements.define('flynt-component', FlyntComponent)

window.Alpine = Alpine
Alpine.start()

import './scripts/scroll.js'
import './scripts/nav.js'
import './scripts/waves.js'
import './scripts/scrollTrigger.js'
import './scripts/june-newsletter.js'
import './scripts/react-form.js'

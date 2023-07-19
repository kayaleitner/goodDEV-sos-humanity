import 'vite/modulepreload-polyfill'
import './scripts/loadCustomElements'
import './scripts/custom'
import Alpine from 'alpinejs'
import FlyntComponent from './scripts/FlyntComponent'
import scrollTrigger from './scripts/scrollTrigger'
import lazySizes from 'lazysizes'

lazySizes.cfg.preloadAfterLoad = true

if (import.meta.env.DEV) {
  import('@vite/client')
}

import.meta.glob([
  '../Components/**',
  '../assets/**',
  '!**/*.js',
  '!**/*.scss',
  '!**/*.php',
  '!**/*.twig',
  '!**/screenshot.png',
  '!**/*.md',
])

window.customElements.define('flynt-component', FlyntComponent)

window.Alpine = Alpine
Alpine.data('scrollTrigger', scrollTrigger)
Alpine.start()

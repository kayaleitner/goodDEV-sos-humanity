const domain = 'backendforth.local/'
const dest = './dist'

const host = `https://${domain}`

const entries = [
  './assets/admin.js',
  './assets/admin.css',
  './assets/main.js',
  './assets/main.css',
  './assets/print.css',
  './assets/editor-style.css',
]

const watchFiles = [
  '*.php',
  'templates/**/*',
  'lib/**/*',
  'inc/**/*',
  './Components/**/*.{php,twig}',
]

module.exports = {
  dest,
  host,
  domain,
  entries,
  watchFiles,
}

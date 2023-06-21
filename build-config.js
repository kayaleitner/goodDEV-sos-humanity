const domain = 'crossboundaryenergy.local/'
const dest = './dist'

const host = `http://${domain}`

const entries = [
  './assets/admin.js',
  './assets/admin.scss',
  './assets/main.js',
  './assets/main.scss',
  './assets/print.scss',
  './assets/editor-style.scss'
]

const watchFiles = [
  '*.php',
  'templates/**/*',
  'lib/**/*',
  'inc/**/*',
  './Components/**/*.{php,twig}'
]

module.exports = {
  dest,
  host,
  domain,
  entries,
  watchFiles
}

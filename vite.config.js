import { defineConfig, loadEnv } from 'vite'
import autoprefixer from 'autoprefixer'
import flynt from './vite-plugin-flynt'
import FullReload from 'vite-plugin-full-reload'
import fs from 'fs'

const wordpressHost = 'http:/starterb.local'

const fontFileNames = [
  'soehne-buch-kursiv.woff2',
  'soehne-buch.woff2',
  'soehne-halbfett-kursiv.woff2',
  'soehne-halbfett.woff2',
  'soehne-kraftig-kursiv.woff2',
  'soehne-kraftig.woff2',
]

const dest = './dist'

const entries = [
  './assets/admin.js',
  './assets/admin.css',
  './assets/main.js',
  './assets/main.css',
  './assets/print.css',
  './assets/editor-style.css'
]

const watchFiles = [
  '*.php',
  'templates/**/*',
  'lib/**/*',
  'inc/**/*',
  './Components/**/*.{php,twig}'
]

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')
  const host = env.VITE_DEV_SERVER_HOST || wordpressHost
  const isSecure = host.indexOf('https://') === 0 && (env.VITE_DEV_SERVER_KEY || env.VITE_DEV_SERVER_CERT)

  return {
    base: './',
    css: {
      devSourcemap: true,
    },
    resolve: {
      alias: {
        '@': __dirname,
      },
    },
    plugins: [flynt({ dest, host }), FullReload(watchFiles)],
    server: {
      https: isSecure
        ? {
          key: fs.readFileSync(env.VITE_DEV_SERVER_KEY),
          cert: fs.readFileSync(env.VITE_DEV_SERVER_CERT),
        }
        : false,
      host: 'localhost', // preserve conflicts with IpV6
    },
    build: {
      // generate manifest.json in outDir
      manifest: true,
      outDir: dest,
      rollupOptions: {
        // overwrite default .html entry
        input: entries,
        output: {
          assetFileNames: (file) =>
            fontFileNames.includes(file.name)
              ? `assets/[name].[ext]`
              : `assets/[name]-[hash].[ext]`,
        },
      },
    },
  }
})

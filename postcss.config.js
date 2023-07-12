module.exports = {
  plugins: [
    require('postcss-import'),
    require('tailwindcss/nesting'),
    require('postcss-nested-ancestors'),
    require('tailwindcss'),
    require('autoprefixer'),
  ],
}

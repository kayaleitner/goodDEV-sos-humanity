import postcssFunctions from 'postcss-functions';

export default {
  plugins: {
    'postcss-import': {},
    'tailwindcss/nesting': {},
    'postcss-nested-ancestors': {},
    'postcss-functions': {
      functions: {
        rem: (value) => {
          const number = parseFloat(value);
          return `calc(${number} / 16 * 1rem)`;
        },
      },
    },
    'postcss-calc': {}, // This should come after postcss-functions
    tailwindcss: {},
    autoprefixer: {},
  },
};

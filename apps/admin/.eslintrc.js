module.exports = {
  env: {
    browser: true,
    es2021: true,
    jquery: true,
  },
  globals: {
    moment: 'readonly',
    get_meta_content: 'readonly',
    get_url_parameter: 'readonly',
    WindowDialog: 'readonly',
    MessageConfirm: 'readonly',
    MessageBox: 'readonly',
    google: 'readonly',
    is_function: 'readonly',
    in_array: 'readonly',
  },
  extends: [
    'plugin:vue/vue3-strongly-recommended',
    '@vue/eslint-config-airbnb-with-typescript',
    '@vue/eslint-config-airbnb-with-typescript/allow-js-in-vue',
  ],
  plugins: [
    '@typescript-eslint/eslint-plugin',
    'import',
    'vue',
  ],
  settings: {
    'import/parsers': {
      '@typescript-eslint/parser': ['.ts'],
    },
    'import/resolver': {
      typescript: {},
    },
  },
  ignorePatterns: [
    '**/vendor/*.js',
    '**/vendor/**/*.js',
    '**/libs/*.js',
    '**/libs/**/*.js',
    '**/plugins/*.js',
    '**/plugins/**/*.js',
  ],
  rules: {
    '@typescript-eslint/no-namespace': ['error', { allowDeclarations: true }],
    'import/extensions': ['error', {
      js: 'never',
      ts: 'never',
      json: 'always',
      vue: 'always',
    }],
    'vue/multi-word-component-names': ['error', {
      ignores: ['index'],
    }],
    'import/order': ['error', {
      'newlines-between': 'always',
      alphabetize: {
        order: 'asc',
        caseInsensitive: true,
      },
      groups: [
        ['builtin', 'external'],
        'internal',
        ['parent', 'sibling'],
      ],
    }],

    'no-underscore-dangle': ['error', {
      allowAfterThis: true,
    }],
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',

    semi: ['error', 'never'],
    'max-len': 'off',
    'linebreak-style': 'off',
    camelcase: [
      'error',
      {
        properties: 'never',
        ignoreDestructuring: true,
        ignoreImports: true,
      },
    ],

    // typescript
    '@typescript-eslint/explicit-member-accessibility': 'off',
    '@typescript-eslint/explicit-function-return-type': 'off',
    '@typescript-eslint/ban-ts-ignore': 'off',
    '@typescript-eslint/prefer-ts-expect-error': 'error',
    '@typescript-eslint/ban-ts-comment': 'warn',
    '@typescript-eslint/explicit-module-boundary-types': 'off',
    '@typescript-eslint/semi': ['error', 'never'],
    '@typescript-eslint/member-delimiter-style': ['error', {
      multiline: {
        delimiter: 'none',
      },
    }],
    '@typescript-eslint/no-empty-function': 'off',
    '@typescript-eslint/no-unused-vars': 'warn',
    'no-return-await': 'off',
    '@typescript-eslint/no-empty-interface': 'off',
    'max-classes-per-file': 'off',
    'no-restricted-globals': 'off',
    'global-require': 'off',
    'no-shadow': 'off',
    'func-names': 'off',
    'object-curly-newline': ['error', {
      consistent: true,
    }],
    'no-plusplus': 'off',
    '@typescript-eslint/no-this-alias': 'off',
    'no-tabs': 'off',
    'no-new': 'off',
    'implicit-arrow-linebreak': 'off',
    'import/prefer-default-export': 'off',
  },
}

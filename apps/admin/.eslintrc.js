const { defineConfig } = require('eslint-define-config')

module.exports = defineConfig({
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
    'plugin:vue/vue3-recommended',
    '@vue/eslint-config-airbnb-with-typescript',
    '@vue/eslint-config-airbnb-with-typescript/allow-js-in-vue',
  ],
  plugins: [
    '@typescript-eslint/eslint-plugin',
    'import',
    'vue',
    'unused-imports',
    'simple-import-sort',
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
    'simple-import-sort/imports': ['warn', {
      groups: [
        // general dependencies
        ['^vue$', '^vue'],
        // node_modules
        ['^@?\\w'],
        // Root
        ['^~resources'],
        // Aliases
        ['^~api'],
        ['^~lib'],
        ['^~components'],
        // `./*.vue` or `../*.vue`
        ['^\\.\\.?\\/.*.vue'],
        // `./*` or `../*`
        ['^\\.\\.?\\/.*\\.(\\w+)'],
        // Side effects like `import 'foo'`
        ['^\\u0000'],
      ],
    }],
    '@typescript-eslint/no-namespace': ['error', { allowDeclarations: true }],
    'import/extensions': ['error', {
      js: 'never',
      ts: 'never',
      json: 'always',
      vue: 'always',
    }],
    'no-underscore-dangle': ['error', {
      allowAfterThis: true,
    }],
    'no-console': 'off',
    'no-debugger': 'off',
    'max-lines': ['error', 300],

    'semi': ['error', 'never'],
    'max-len': 'off',
    'linebreak-style': 'off',
    'camelcase': [
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
    'import/no-extraneous-dependencies': ['error', {
      devDependencies: ['**/*.d.ts'],
    }],
    'vue/max-len': 'off',
    'no-unused-vars': 'off',
    '@typescript-eslint/no-unused-vars': 'off',
    'unused-imports/no-unused-imports': 'error',
    'unused-imports/no-unused-vars': [
      'warn',
      {
        vars: 'all',
        varsIgnorePattern: '^_',
        args: 'after-used',
        argsIgnorePattern: '^_',
      },
    ],
    'quote-props': ['error', 'consistent-as-needed'],
  },
  overrides: [
    {
      files: ['*.vue'],
      rules: {
        'max-lines': ['error', 600],
        'vue/max-len': ['error', {
          code: 100,
          template: 100,
          tabWidth: 2,
          comments: 100,
          ignorePattern: '',
          ignoreComments: false,
          ignoreTrailingComments: false,
          ignoreUrls: false,
          ignoreStrings: true,
          ignoreTemplateLiterals: true,
          ignoreRegExpLiterals: true,
          ignoreHTMLAttributeValues: false,
          ignoreHTMLTextContents: true,
        }],
        'vue/singleline-html-element-content-newline': 'off',
        'vue/object-curly-newline': 'off',
      },
    },
    {
      files: ['vite.config.ts', '.eslintrc.js'],
      rules: {
        'import/no-extraneous-dependencies': 'off',
      },
    },
  ],
})

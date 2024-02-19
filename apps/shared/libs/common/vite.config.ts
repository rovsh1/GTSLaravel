import fs from 'fs'
import path from 'node:path'
import postcssPresetEnv from 'postcss-preset-env'
import { defineConfig } from 'vite'
import checker from 'vite-plugin-checker'
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js'
import dts from 'vite-plugin-dts'
import tsconfigPaths from 'vite-tsconfig-paths'

import { scripts } from './package.json'

/* eslint-disable */
const files = fs.readdirSync('./src/helpers')

const components = files.reduce((obj, component) => {
  obj[component.split('.')[0]] = `src/helpers/${component}`
  return obj
}, {})

components['timezone'] = 'src/support/timezone.js'
components['date-picker'] = 'src/widgets/date-picker/date-picker.ts'
components['popover'] = 'src/widgets/popover/popover.ts'
components['dialog'] = 'src/widgets/dialog/helpers.js'
components['select-element'] = 'src/widgets/select-element/select-element.ts'
/* eslint-enable */

export default defineConfig(() => ({
  build: {
    emptyOutDir: true,
    cssCodeSplit: true,
    lib: {
      entry: components,
      fileName: (format, entryName) => {
        if (format === 'es') return `${entryName}/${entryName}.js`
        return `${entryName}/${entryName}.${format}`
      },
    },
    rollupOptions: {
      external: ['luxon', 'lodash', 'litepicker', 'cleave.js', 'bootstrap', 'select2', 'jquery'],
    },
  },
  plugins: [
    cssInjectedByJsPlugin({ relativeCSSInjection: true }),
    tsconfigPaths(),
    checker({
      enableBuild: false,
      typescript: true,
      eslint: {
        lintCommand: scripts['lint:scripts'],
      },
      stylelint: {
        lintCommand: scripts['lint:styles'].replace(/"/, ''),
      },
    }),
    dts({
      outDir: './dist/types',
      insertTypesEntry: true,
    }),
  ],
  resolve: {
    alias: {
      '~helpers': path.resolve(__dirname, 'src/helpers'),
      '~widgets': path.resolve(__dirname, 'src/widgets'),
      '~support': path.resolve(__dirname, 'src/support'),
    },
  },
  css: {
    postcss: {
      plugins: [
        postcssPresetEnv,
      ],
    },
  },
}))

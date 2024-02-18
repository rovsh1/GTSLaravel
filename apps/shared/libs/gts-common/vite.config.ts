import path from 'node:path'
import postcssPresetEnv from 'postcss-preset-env'
import { defineConfig } from 'vite'
import checker from 'vite-plugin-checker'
import tsconfigPaths from 'vite-tsconfig-paths'
import dts from 'vite-plugin-dts'
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js'

import { scripts } from './package.json'

export default defineConfig(({ command }) => ({
  build: {
    emptyOutDir: true,
    cssCodeSplit: true,
    lib: {
        entry: {
          date: path.resolve(__dirname, 'src/helpers/date.ts'),
          timezone: path.resolve(__dirname, 'src/support/timezone.js')
        },
        fileName: (format, entryName) => {
          if(format === 'es')
            return `${entryName}.js`
          return `${entryName}.${format}`
        },       
    },
    rollupOptions: {
        external: ["luxon","lodash","litepicker","cleave.js","bootstrap","select2","jquery"],
        output: {
          assetFileNames: '[name]/[name].[ext]',
        }
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
      outDir: './dist/types'
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

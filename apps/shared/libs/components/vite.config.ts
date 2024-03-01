import vue from '@vitejs/plugin-vue'
import fs from 'fs'
import path from 'node:path'
import postcssPresetEnv from 'postcss-preset-env'
import { defineConfig } from 'vite'
import checker from 'vite-plugin-checker'
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js'
import dts from 'vite-plugin-dts'
import tsconfigPaths from 'vite-tsconfig-paths'

import { scripts } from './package.json'

const filesBaseComponents = fs.readdirSync('./src').filter((file) => file.includes('.vue'))
const filesEditableComponents = fs.readdirSync('./src/Editable').filter((file) => file.includes('.vue'))

/* eslint-disable */
const componentsBase = filesBaseComponents.reduce((obj, component) => {
  obj[component.split('.')[0]] = `src/${component}`

  return obj
}, {})

const componentsEditable = filesEditableComponents.reduce((obj, component) => {
  obj[`Editable/${component.split('.')[0]}`] = `src/Editable/${component}`
  return obj
}, {})
/* eslint-enable */

export default defineConfig(({ command }) => ({
  build: {
    target: 'esnext',
    cssCodeSplit: true,
    copyPublicDir: false,
    lib: { 
      entry: {
        ...componentsBase,
        ...componentsEditable,
      },
      fileName: (format, entryName) => {
        const moduleFolder = entryName.split('/')[0]
        const moduleName = entryName.split('/')[1]
        const modulePath = moduleName ? `${moduleFolder}/${moduleName}/${moduleName}` : `${moduleFolder}/${moduleFolder}`
        if (format === 'es') return `${modulePath}.js`
        return `${modulePath}.${format}`
      },  
    },
    rollupOptions: {
      external: ['vue'],
      output: {
        globals: { vue: 'Vue' },
      },
    },
  },
  plugins: [
    cssInjectedByJsPlugin({ relativeCSSInjection: true }),
    dts({ entryRoot: './src', cleanVueFileName: true }),
    tsconfigPaths(),
    vue({
      isProduction: command === 'build',
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    checker({
      enableBuild: false,
      vueTsc: true,
      typescript: true,
      eslint: {
        lintCommand: scripts['lint:scripts'],
      },
      stylelint: {
        lintCommand: scripts['lint:styles'].replace(/"/, ''),
      },
    }),
  ],
  resolve: {
    alias: {
      '~components': path.resolve(__dirname, 'src'),
      '~assets': path.resolve(__dirname, 'assets'),
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

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

const filesBaseComponents = fs.readdirSync('./src/Base').filter((file) => file.includes('.vue'))
const filesEditableComponents = fs.readdirSync('./src/Editable').filter((file) => file.includes('.vue'))
const filesBootstrapButtonComponents = fs.readdirSync('./src/Bootstrap/BootstrapButton')
const filesBootstrapCardComponents = fs.readdirSync('./src/Bootstrap/BootstrapCard')
const filesBootstrapCheckBoxComponents = fs.readdirSync('./src/Bootstrap/BootstrapCheckBox')
const filesBootstrapTabsComponents = fs.readdirSync('./src/Bootstrap/BootstrapTabs')
const filesBootstrapToastComponents = fs.readdirSync('./src/Bootstrap/BootstrapToast')

/* eslint-disable */
const componentsBase = filesBaseComponents.reduce((obj, component) => {
  obj[`Base/${component.split('.')[0]}`] = `src/Base/${component}`
  return obj
}, {})
const componentsEditable = filesEditableComponents.reduce((obj, component) => {
  obj[`Editable/${component.split('.')[0]}`] = `src/Editable/${component}`
  return obj
}, {})
const componentsBootstrapButton = filesBootstrapButtonComponents.reduce((obj, component) => {
  obj[`Bootstrap/BootstrapButton/${component.split('.')[0]}`] = `src/Bootstrap/BootstrapButton/${component}`
  return obj
}, {})
const componentsBootstrapCard = filesBootstrapCardComponents.reduce((obj, component) => {
  obj[`Bootstrap/BootstrapCard/${component.split('.')[0]}`] = `src/Bootstrap/BootstrapCard/${component}`
  return obj
}, {})
const componentsBootstrapCheckBox = filesBootstrapCheckBoxComponents.reduce((obj, component) => {
  obj[`Bootstrap/BootstrapCheckBox/${component.split('.')[0]}`] = `src/Bootstrap/BootstrapCheckBox/${component}`
  return obj
}, {})
const componentsBootstrapTabs = filesBootstrapTabsComponents.reduce((obj, component) => {
  obj[`Bootstrap/BootstrapTabs/${component.split('.')[0]}`] = `src/Bootstrap/BootstrapTabs/${component}`
  return obj
}, {})
const componentsBootstrapToast = filesBootstrapToastComponents.reduce((obj, component) => {
  obj[`Bootstrap/BootstrapToast/${component.split('.')[0]}`] = `src/Bootstrap/BootstrapToast/${component}`
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
        ...componentsBootstrapButton,
        ...componentsBootstrapCard,
        ...componentsBootstrapCheckBox,
        ...componentsBootstrapTabs,
        ...componentsBootstrapToast,
      },
      fileName: (format, entryName) => {
        const modulePath = entryName.split('/')
        const moduleFolder = modulePath.slice(0, -1).join('/')
        const moduleName = modulePath[modulePath.length - 1]
        const moduleBuildPath = `${moduleFolder}/${moduleName}/${moduleName}`
        if (format === 'es') return `${moduleBuildPath}.js`
        return `${moduleBuildPath}.${format}`
      },
    },
    rollupOptions: {
      external: ['vue', '@vueuse/components', '@vueuse/core', 'body-scroll-lock',
        'gts-common', 'litepicker', 'luxon', 'medium-zoom', 'jquery', 'nanoid', 'mosha-vue-toastify', 'floating-vue'],
      output: {
        globals: { vue: 'Vue' },
      },
    },
  },
  plugins: [
    cssInjectedByJsPlugin({ relativeCSSInjection: true }),
    dts({ cleanVueFileName: true }),
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

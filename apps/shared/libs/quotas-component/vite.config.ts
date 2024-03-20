import vue from '@vitejs/plugin-vue'
import path from 'node:path'
import postcssPresetEnv from 'postcss-preset-env'
import { defineConfig } from 'vite'
import checker from 'vite-plugin-checker'
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js'
import dts from 'vite-plugin-dts'
import tsconfigPaths from 'vite-tsconfig-paths'

import { scripts } from './package.json'

export default defineConfig(({ command }) => ({
  build: {
    target: 'esnext',
    cssCodeSplit: true,
    copyPublicDir: false,
    lib: {
      entry: {
        QuotasComponent: path.resolve(__dirname, 'src/QuotasComponent.vue'),
        lib: path.resolve(__dirname, 'src/components/lib/index.ts'),
      },
      name: 'quotas-component',
      formats: ['es', 'cjs'],
      fileName: (format, entryName) => {
        if (format === 'es') return `${entryName}.js`
        return `${entryName}.${format}`
      },
    },
    rollupOptions: {
      external: ['vue', 'qs', '@vueuse/components', '@vueuse/core', '@floating-ui/vue',
        'bootstrap', 'luxon', 'floating-vue', 'nanoid', 'lodash', 'pretty-bytes', 'sass', 'jquery'],
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
      '~assets': path.resolve(__dirname, 'src/assets'),
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

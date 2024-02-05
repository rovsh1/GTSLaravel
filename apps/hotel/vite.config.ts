import vue from '@vitejs/plugin-vue'
import { expand } from 'dotenv-expand'
import { config } from 'dotenv-safe'
import laravel from 'laravel-vite-plugin'
import path from 'node:path'
import postcssPresetEnv from 'postcss-preset-env'
import { defineConfig } from 'vite'
import checker from 'vite-plugin-checker'
import tsconfigPaths from 'vite-tsconfig-paths'

import { scripts } from './package.json'

const conf = expand(config({
  allowEmptyValues: false,
  path: path.resolve(__dirname, '../../.env'),
})).parsed

Object.assign(process.env, {
  APP_URL: conf.HO_URL,
  VITE_API_URL: conf.HO_URL,
})

// https://github.com/laravel/vite-plugin/pull/57
console.log(`The Vite server should not be accessed directly. Please visit ${process.env.APP_URL} instead.`)
export default defineConfig(({ command }) => ({
  build: {
    target: 'esnext',
  },
  esbuild: {
    drop: command === 'serve' ? [] : ['console', 'debugger'],
  },
  plugins: [
    laravel({
      input: [
        'resources/js/support/jquery.ts',
        'resources/views/auth/login/login.scss',
        'resources/views/auth/login/login.ts',
        'resources/views/default/grid/grid.scss',
        'resources/views/default/form/form.scss',
        'resources/views/main.scss',
        'resources/views/main.ts',
        'resources/views/show/show.scss',
        'resources/views/rooms/rooms.scss',
        'resources/views/rooms/rooms.ts',
        'resources/views/images/images.ts',
        'resources/views/quotas/quotas.ts',
        'resources/views/settings/settings.ts',
        'resources/views/settings/settings.scss',
        'resources/views/booking/hotel/main/main.ts',
        'resources/views/booking/hotel/show/show.scss',
        'resources/views/booking/hotel/show/show.ts',
        'resources/views/booking/hotel/timeline/timeline.scss',
        'resources/views/booking/hotel/timeline/timeline.ts',
        'resources/views/profile/profile/profile.ts',
        'resources/views/profile/profile/profile.scss',
        'resources/views/profile/password/form.ts',
      ],
      refresh: [
        'resources/**/*.php',
      ],
      transformOnServe: (code, devServerUrl) =>
        code.replaceAll(`${devServerUrl}/@fs`, devServerUrl),
    }),
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
      '~resources': path.resolve(__dirname, 'resources'),
      '~api': path.resolve(__dirname, 'resources/vue/api'),
      '~components': path.resolve(__dirname, 'resources/vue/components'),
      '~stores': path.resolve(__dirname, 'resources/vue/stores'),
      '~widgets': path.resolve(__dirname, 'resources/js/widgets'),
      '~cache': path.resolve(__dirname, 'resources/js/cache'),
      '~helpers': path.resolve(__dirname, 'resources/js/helpers'),
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

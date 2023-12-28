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

expand(config({
  allowEmptyValues: true,
}))
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
        'resources/assets/jquery.ts',
        'resources/assets/tinymce.js',
        'resources/assets/tinymce-content.scss',
        'resources/views/auth/login/login.scss',
        'resources/views/show/show.scss',
        'resources/views/rooms/rooms.scss',
        'resources/views/rooms/rooms.ts',
        'resources/views/images/images.ts',
        'resources/views/quotas/quotas.ts',
        'resources/views/settings/settings.ts',
        'resources/views/settings/settings.scss',
        'resources/views/booking/hotel/show/show.scss',
        'resources/views/booking/hotel/show/show.ts',
        'resources/views/booking/hotel/timeline/timeline.scss',
        'resources/views/booking/hotel/timeline/timeline.ts',
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
      '~api': path.resolve(__dirname, 'resources/api'),
      '~lib': path.resolve(__dirname, 'resources/lib'),
      '~components': path.resolve(__dirname, 'resources/components'),
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

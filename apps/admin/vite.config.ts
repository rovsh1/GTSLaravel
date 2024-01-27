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
  APP_URL: conf.BO_URL,
  VITE_API_URL: conf.BO_URL,
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
        'resources/assets/jquery.ts',
        'resources/assets/tinymce.js',
        'resources/assets/tinymce-content.scss',
        'resources/views/administration/mail-queue/mail-queue.ts',
        'resources/views/administration/journal/journal.ts',
        'resources/views/administration/access-group-form/access-group-form.ts',
        'resources/views/administration/access-group-form/access-group-form.scss',
        'resources/views/auth/login/login.scss',
        'resources/views/data/city-form/city-form.ts',
        'resources/views/data/landmark-form/landmark-form.ts',
        'resources/views/default/grid/grid.scss',
        'resources/views/default/form/form.scss',
        'resources/views/client/form/form.ts',
        'resources/views/client/user/main/main.ts',
        'resources/views/hotel-user/main/main.ts',
        'resources/views/hotel/form/form.ts',
        'resources/views/hotel/employee/edit/edit.ts',
        'resources/views/hotel/main/main.ts',
        'resources/views/hotel/notes/notes.ts',
        'resources/views/hotel/prices/prices.ts',
        'resources/views/hotel/prices/prices.scss',
        'resources/views/hotel/quotas/quotas.ts',
        'resources/views/hotel/room-form/room-form.ts',
        'resources/views/hotel/room-form/room-form.scss',
        'resources/views/hotel/rooms/rooms.ts',
        'resources/views/hotel/rooms/rooms.scss',
        'resources/views/hotel/show/show.ts',
        'resources/views/hotel/show/show.scss',
        'resources/views/client/show.ts',
        'resources/views/client-document/form/form.ts',
        'resources/views/hotel/images/images.ts',
        'resources/views/hotel/settings/settings.ts',
        'resources/views/hotel/settings/settings.scss',
        'resources/views/hotel/season/edit.ts',
        'resources/views/mail/template/form.ts',
        'resources/views/profile/profile/profile.ts',
        'resources/views/profile/profile/profile.scss',
        'resources/views/supplier/show.js',
        'resources/views/supplier/show.scss',
        'resources/views/supplier/service/price/airport/index.ts',
        'resources/views/supplier/service/price/transfer/index.ts',
        'resources/views/supplier/service/price/other/index.ts',
        'resources/views/supplier/service/cancel-conditions/transfer/index.ts',
        'resources/views/supplier/service/cancel-conditions/service/index.ts',
        'resources/views/supplier/service/form/form.ts',
        'resources/views/supplier/contract/form/form.ts',
        'resources/views/supplier/season/form/form.ts',
        'resources/views/booking/hotel/main/main.ts',
        'resources/views/booking/hotel/form/form.ts',
        'resources/views/booking/hotel/show/show.ts',
        'resources/views/booking/hotel/show/show.scss',
        'resources/views/booking/hotel/timeline/timeline.ts',
        'resources/views/booking/hotel/timeline/timeline.scss',
        'resources/views/booking/services/timeline/timeline.ts',
        'resources/views/booking/services/timeline/timeline.scss',
        'resources/views/booking/services/form/form.ts',
        'resources/views/booking/services/show/show.ts',
        'resources/views/booking/services/show/show.scss',
        'resources/views/booking/services/main/main.ts',
        'resources/views/booking-order/form/form.ts',
        'resources/views/booking-order/show/show.ts',
        'resources/views/booking-order/show/show.scss',
        'resources/views/payment/main/main.ts',
        'resources/views/payment/form/form.ts',
        'resources/views/markup-group/rules/form/form.ts',
        'resources/views/locale-dictionary/locale-dictionary.ts',
        'resources/views/locale-dictionary/locale-dictionary.scss',
        'resources/views/main.scss',
        'resources/views/main.ts',
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

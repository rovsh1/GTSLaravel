import vue from '@vitejs/plugin-vue'
import { config } from 'dotenv-safe'
import laravel from 'laravel-vite-plugin'
import path from 'node:path'
import { defineConfig } from 'vite'
import checker from 'vite-plugin-checker'
import tsconfigPaths from 'vite-tsconfig-paths'

import { scripts } from './package.json'

config({
  allowEmptyValues: true,
  path: '../../.env',
  example: '../../.env.example',
})
// https://github.com/laravel/vite-plugin/pull/57
console.log(`The Vite server should not be accessed directly. Please visit ${process.env.APP_URL} instead.`)
export default defineConfig(({ command }) => ({
  build: {
    target: 'esnext',
  },
  esbuild: {
    drop: ['console', 'debugger'],
  },
  plugins: [
    laravel({
      input: [
        'resources/assets/jquery.ts',
        'resources/assets/tinymce.js',
        'resources/assets/tinymce-content.scss',
        'resources/views/administration/city-form/city-form.ts',
        'resources/views/administration/landmark-form/landmark-form.ts',
        'resources/views/administration/access-group-form/access-group-form.ts',
        'resources/views/administration/access-group-form/access-group-form.scss',
        'resources/views/auth/login/login.scss',
        'resources/views/default/grid/grid.scss',
        'resources/views/default/form/form.scss',
        'resources/views/file-manager/file-manager.ts',
        'resources/views/file-manager/file-manager.scss',
        'resources/views/hotel/edit/edit.ts',
        'resources/views/hotel/employee/edit/edit.ts',
        'resources/views/hotel/main/main.ts',
        'resources/views/hotel/notes/notes.ts',
        'resources/views/hotel/notes/notes.scss',
        'resources/views/hotel/prices/prices.ts',
        'resources/views/hotel/prices/prices.scss',
        'resources/views/hotel/quotas/quotas.ts',
        'resources/views/hotel/room-form/room-form.ts',
        'resources/views/hotel/room-form/room-form.scss',
        'resources/views/hotel/rooms/rooms.ts',
        'resources/views/hotel/rooms/rooms.scss',
        'resources/views/hotel/show/show.ts',
        'resources/views/hotel/show/show.scss',
        'resources/views/hotel/images/images.ts',
        'resources/views/hotel/settings/settings.ts',
        'resources/views/profile/profile/profile.ts',
        'resources/views/profile/profile/profile.scss',
        'resources/views/service-provider/show.js',
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
    },
  },
}))

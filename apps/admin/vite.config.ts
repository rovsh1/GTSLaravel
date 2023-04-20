/* eslint import/no-extraneous-dependencies: ["error", {"devDependencies": true}] */
import { config } from 'dotenv-safe'
import laravel from 'laravel-vite-plugin'
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
import path from 'node:path'
import { defineConfig } from 'vite'
import tsconfigPaths from 'vite-tsconfig-paths'

config({
  allowEmptyValues: true,
  path: '../../.env',
  example: '../../.env.example',
})
// https://github.com/laravel/vite-plugin/pull/57
console.log(`The Vite server should not be accessed directly. Please visit ${process.env.APP_URL} instead.`)
export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/assets/jquery',
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
        'resources/views/hotel/room-form/room-form.ts',
        'resources/views/hotel/room-form/room-form.scss',
        'resources/views/hotel/rooms/rooms.ts',
        'resources/views/hotel/rooms/rooms.scss',
        'resources/views/hotel/show/show.ts',
        'resources/views/hotel/show/show.scss',
        'resources/views/profile/profile/profile.ts',
        'resources/views/profile/profile/profile.scss',
        'resources/views/service-provider/show.js',
        'resources/views/main.scss',
        'resources/views/main.ts',
      ],
      refresh: true,
      transformOnServe: (code, devServerUrl) =>
        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        // @ts-expect-error
        code.replaceAll(`${devServerUrl}/@fs`, devServerUrl),
    }),
    tsconfigPaths(),
  ],
  resolve: {
    alias: {
      '~resources': path.resolve(__dirname, 'resources'),
    },
  },
})

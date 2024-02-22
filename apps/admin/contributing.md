# Contributing

## Architecture

This is a Laravel front-end for administrating purposes.

Views is declared in PHP controllers. You can find them by `view('` search query.

## Folders

- `app`, `factories`, `routes` and `tests` is PHP field
- `public` is static assets
- `resources` is frontend sources

## Views

Using [Laravel Blade](https://laravel.com/docs/10.x/blade)

Each view is a `.blade.php` file extending one of layouts in `resources/views`.

## Vite and static assets

Static assets such as `.css`, `.js` or `.svg` are built into `public/build` directory.

View can have `.css` or `.js` files attached using `@vite` macros with path relative to admin root (`apps/admin`).

Any path used in `@vite` macros is added to `vite.config.ts` to be built into static asset and mentioned in `public/build/manifest.json` to be available after build in production mode.

Any assets used in `.css` or `.js` is imported using Vite aliases to be built into static asset and have hashed filename to avoid of caching issues.

## Frontend

Stack:

- Node.js
- npm
- TypeScript
- SCSS
- Vue

Use [Volta](https://volta.sh) to maintain Node version.

All frontend configurations are in app root folder, like `.eslintrc` or `tsconfig.json`.

Import / Resolve aliases is mentioned in these configurations:

- `vite.config.ts` › `resolve` › `alias`
- `tsconfig.json` › `compilerOptions` › `paths` 
- `.eslintrc.js` › `rules` › `simple-import-sort/imports`

Please keep them in sync while maintaining.

## Linters and git hooks

To not let you commit broken code, [`lefthook`](https://github.com/evilmartians/lefthook) is used.

> It's configuration is on project root (outside of `apps/admin`) because it's required this library to work. Feel free to move it's config to `apps/admin` if you know how.

When you run `npm install`, Lefthook runs it's `postinstall` and add some to `.git` folder. After that, each time you try to commit something, linters are launched against files in index, but type checks are required to run against all `.ts` or `.vue` files.

## UI

As UI library, [Bootstrap](https://getbootstrap.com) is used.

## API

To fetch backend, `useFetch` is used from [VueUse](https://vueuse.org) composable collection. It's a simple solution to have a reactive interface with native `fetch`.

If we want to go further, [`swrv`](https://github.com/Kong/swrv) or [`vue-query`](https://www.npmjs.com/package/@tanstack/vue-query) can be used instead.

## Date and time

To work with date and time, [Luxon](https://moment.github.io/luxon) is used.

Please note that [`moment` is deprecated](https://momentjs.com/docs/#/-project-status) by authors and community is migrated to Luxon.

## Validation

[Zod](https://zod.dev) is used to validate data and provide validated typed schema.

[VeeValidate](https://vee-validate.logaretm.com/v4) is used for UI in Vue components.

# Floating UI

For tooltips, dropdowns and other popup-like UI elements, [`floating-vue`](https://floating-vue.starpad.dev) and [Floating UI](https://floating-vue.starpad.dev) are used.

# Icons

For icons, [`Google Material Symbols`](https://fonts.google.com/icons) is used.

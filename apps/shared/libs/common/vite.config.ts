import fs from "fs"
import { resolve } from 'node:path'
import { defineConfig } from 'vite'
import checker from 'vite-plugin-checker'
import tsconfigPaths from 'vite-tsconfig-paths'
import dts from 'vite-plugin-dts'
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js'

const files = fs.readdirSync("./src/helpers");

const components = files.reduce((obj, component) => {
  obj[component.split(".")[0]] = `src/helpers/${component}`;
  return obj;
}, {});

components['timezone'] = 'src/support/timezone.js';
components['date-picker'] = 'src/widgets/date-picker/date-picker.ts';
components['popover'] = 'src/widgets/popover/popover.ts';
components['dialog'] = 'src/widgets/dialog/helpers.js';
components['select-element'] = 'src/widgets/select-element/select-element.ts';

export default defineConfig(({ command }) => ({
  build: {
    cssCodeSplit: true,
    lib: {
        entry: components,
        name: 'gts-common',
        fileName: (format, entryName) => {
          if(format === 'es')
            return `${entryName}/index.js`
          return `${entryName}/index.${format}`
        },       
    },
    rollupOptions: {
        external: ["luxon","lodash","litepicker","cleave.js","bootstrap","select2","jquery"],
        output: {
          assetFileNames: '[name]/[name].[ext]',
        },
    },
  },
  alias: {
    '~helpers': resolve(__dirname, 'src/helpers'),
    '~widgets': resolve(__dirname, 'src/widgets'),
  },
  plugins: [
    cssInjectedByJsPlugin({ relativeCSSInjection: true }),
    tsconfigPaths(),
    checker({
      enableBuild: false,
      typescript: true,
    }),
    dts({ 
      outDir: 'dist/types',
      include: ['src/helpers', 'src/widgets', 'src/support'],
    }),
  ],
}))

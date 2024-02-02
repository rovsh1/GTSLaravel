import { App } from 'vue'

import FloatingVue from 'floating-vue'

import './style.scss'

export const installFloatingVue = (app: App) => {
  app.use(FloatingVue, {
    themes: {
      'danger-tooltip': {
        $extend: 'tooltip',
      },
    },
  })
}

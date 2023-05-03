import { ComponentOptions, createApp, h, Ref, unref } from 'vue'

import { VTooltip } from 'floating-vue'

import '~resources/sass/vendor/floating-vue.scss'

type CreateVueInstanceParams = {
  rootComponent: ComponentOptions
  rootContainer: string
}
export const createVueInstance = (params: CreateVueInstanceParams) => {
  const { rootComponent, rootContainer } = params

  const app = createApp({
    render: () => h(rootComponent),
  })

  app.directive('tooltip', VTooltip)

  app.mount(rootContainer)

  return app
}

export const getRef = <T, R>(ref: T | Ref<T>, getter: (data: T) => R) => getter(unref(ref))

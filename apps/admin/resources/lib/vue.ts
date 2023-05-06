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

export const getNullableRef = <T, R, N = null>(
  ref: T | null | Ref<T | null>,
  onSome: (data: T) => R,
  onNull: N | null = null,
): R | N => {
  const unwrapped = unref(ref)
  if (unwrapped === null) return onNull as N
  return onSome(unwrapped)
}

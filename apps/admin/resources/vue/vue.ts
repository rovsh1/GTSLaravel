import { Component, createApp, h, Plugin, Ref, unref } from 'vue'

import { installFloatingVue } from '~widgets/tooltip/install-tooltip'

type CreateVueInstanceParams = {
  rootComponent: Component
  rootContainer: string
  plugins?: Plugin[]
}
export const createVueInstance = (params: CreateVueInstanceParams) => {
  const { rootComponent, rootContainer } = params

  const rootContainerElement = document.querySelector<HTMLElement>(rootContainer)
  if (rootContainerElement === null) {
    throw new Error('Root container not found')
  }

  const app = createApp({
    render: () => h(rootComponent),
  })

  installFloatingVue(app)

  params.plugins?.forEach((plugin): void => {
    app.use(plugin)
  })
  app.mount(rootContainer)

  return app
}

export type RefGetter<T, R> = (data: T) => R

export const getRef = <T, R>(ref: T | Ref<T>, getter: RefGetter<T, R>) => getter(unref(ref))

export const getNullableRef = <T, R, N = null>(
  ref: T | null | Ref<T | null>,
  onSome: RefGetter<T, R>,
  onNull: N | null = null,
): R | N => {
  const unwrapped = unref(ref)
  if (unwrapped === null) return onNull as N
  return onSome(unwrapped)
}

import { ComponentOptions, createApp, h, inject, Ref, unref } from 'vue'

import { ZodObject, ZodRawShape } from 'zod/lib/types'

import { parseInitialData } from '~lib/initial-data'
import { installFloatingVue } from '~lib/tooltip/install-tooltip'

const initialDataKey = 'initial'

type CreateVueInstanceParams = {
  rootComponent: ComponentOptions
  rootContainer: string
}
export const createVueInstance = (params: CreateVueInstanceParams) => {
  const { rootComponent, rootContainer } = params

  const rootContainerElement = document.querySelector<HTMLElement>(rootContainer)
  if (rootContainerElement === null) {
    throw new Error('Root container not found')
  }

  const initialData = rootContainerElement
    .getAttribute(`data-vue-${initialDataKey}`)

  const app = createApp({
    render: () => h(rootComponent),
  })

  app.provide(initialDataKey, initialData === null
    ? {}
    : JSON.parse(initialData))

  installFloatingVue(app)

  app.mount(rootContainer)

  return app
}

export const injectInitialData = <T extends ZodRawShape>(schema: ZodObject<T>) =>
  parseInitialData(schema, inject(initialDataKey))

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

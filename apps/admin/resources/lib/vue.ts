import { ComponentOptions, createApp, h, inject, Ref, unref } from 'vue'

import { VTooltip } from 'floating-vue'

import '~resources/sass/vendor/floating-vue.scss'

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

  app.directive('tooltip', VTooltip)

  app.mount(rootContainer)

  return app
}

type InitialDataValue = string | number

type InjectInitialDataValidations<T> = Record<keyof T, 'string' | 'number'>

export const injectInitialData = <T extends Record<string, InitialDataValue>>(validations: InjectInitialDataValidations<T>) => {
  const injected = inject<T>(initialDataKey)
  if (injected === undefined) {
    throw new Error('Initial data not provided')
  }
  Object.keys(injected).forEach((key) => {
    const value = injected[key]
    const valueType = typeof value
    const requiredType = validations[key]
    if (valueType !== requiredType) {
      throw new TypeError(`Initial data type mismatch: '${key}' must be a number, got '${valueType}' instead: '${value}'`)
    }
  })
  return injected
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

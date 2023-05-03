import { ComponentOptions, createApp, h, Ref, unref } from 'vue'

type CreateVueInstanceParams = {
  rootComponent: ComponentOptions
  rootContainer: string
}
export const createVueInstance = (params: CreateVueInstanceParams) => {
  const { rootComponent, rootContainer } = params

  const app = createApp({
    render: () => h(rootComponent),
  })

  app.mount(rootContainer)

  return app
}

export const getRef = <T, R>(ref: T | Ref<T>, getter: (data: T) => R) => getter(unref(ref))

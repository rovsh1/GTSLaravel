import { ComponentOptions, createApp, h } from 'vue'

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

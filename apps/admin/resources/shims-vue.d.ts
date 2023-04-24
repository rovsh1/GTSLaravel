// Mocks all files ending in `.vue` showing them as plain Vue instances
declare module '*.vue' {
  import type { ComponentOptions } from 'vue'

  const component: ComponentOptions
  export default component
}

declare module '*.svg' {
  export default string
}

import { ZodRawShape } from 'zod/lib/types'

declare global {
  interface JQuery extends JQueryStatic {
    update(): void
  }

  interface Window {

    WindowDialog(options: any): void

    MessageConfirm(message: string, title: string, fn: any): void

    MessageBox(message: string, title: string, type: string): void

    isTypeFn: {
      is_function(value: any): boolean
    }
    coreFn: {
      in_array<T = string | number>(value: T, array: T[]): boolean
    }

    $: JQueryStatic
    jQuery: JQueryStatic

    [key: string]: ZodRawShape

  }
}

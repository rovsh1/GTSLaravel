import type { TinyMCE } from 'tinymce'

export {}

declare global {
  interface JQuery extends JQueryStatic {
    elementCity(options: any): JQuery<HTMLElement>

    coordinatesInput(options: any): JQuery<HTMLElement>

    cardContacts(options: any = {}): JQuery<HTMLElement>

    sortable(options: any): JQuery<HTMLElement>

    update(): void

    deleteButton(): void
  }

  interface Window {
    get_meta_content(name: string, parse?: boolean): string | any

    get_url_parameter(name: string, url?: string): string | null

    WindowDialog(options: any): void

    MessageConfirm(message: string, title: string, fn: any): void

    MessageBox(message: string, title: string, type: string): void

    isTypeFn: {
      is_function(value: any): boolean
    }
    coreFn: {
      in_array(value: any, array: any): boolean
    }

    google: any
    $: JQueryStatic
    jQuery: JQueryStatic

    tinymce: TinyMCE
  }
}
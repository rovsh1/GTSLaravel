import type { TinyMCE } from 'tinymce'

declare global {
  interface JQuery extends JQueryStatic {
    elementCity(options: any): JQuery<HTMLElement>

    childCombo(options: any): JQuery<HTMLElement>

    passwordViewer(): JQuery<HTMLElement>

    coordinatesInput(options: any): JQuery<HTMLElement>

    cardContacts(options: any = {}): JQuery<HTMLElement>

    sortable(options: any): JQuery<HTMLElement>

    update(): void

    deleteButton(): void

    multiselect(options: { popupCls: string }): JQuery<HTMLElement>
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
      in_array<T = string | number>(value: T, array: T[]): boolean
    }

    google: any
    $: JQueryStatic
    jQuery: JQueryStatic

    tinymce: TinyMCE
  }
}

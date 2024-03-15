import jquery from 'jquery'

declare global {
  interface Window {
    $: JQueryStatic
    jQuery: JQueryStatic
  }
}

window.$ = jquery
window.jQuery = jquery

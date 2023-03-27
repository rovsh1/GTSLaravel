import processHtml from './html-processor'
import load from './loader'
import {
  defaultOptions,
  createElement,
  createBootstrapModal,
  boot,
  bindEvents,
} from './modal-builder'

export default class Modal {
  #options

  #el

  #bootstrapModal

  #eventHandlers = []

  constructor(options) {
    options = { ...defaultOptions, ...options }

    const $el = this.#el = createElement(options)

    this.#bootstrapModal = createBootstrapModal(this, $el, options)

    bindEvents(this, options)

    this.#options = options

    boot(this, options)

    this.trigger('booted')
  }

  get bootstrapModal() { return this.#bootstrapModal }

  get el() { return this.#el }

  get dialog() { return this.#el.find('>div.modal-dialog') }

  get content() { return this.dialog.find('>div.modal-content') }

  get header() { return this.content.find('>div.modal-header') }

  get body() { return this.content.find('>div.modal-body') }

  get footer() { return this.content.find('>div.modal-footer') }

  get form() { return this.content.find('form') }

  get(name) { return this.#options[name] }

  set(name, value) { this.#options[name] = value }

  setTitle(text) {
    this.header.find('>.modal-title').html(text)
  }

  setHtml(html) {
    this.body.html(html)

    if (this.get('processHtml')) {
      processHtml(this, this.body)
    }

    this.trigger('update')
  }

  setLoading(flag) {
    if (this.#el) {
      this.content[flag ? 'addClass' : 'removeClass']('loading')
    }
  }

  load(params) {
    load(this, params)
  }

  submit() { this.form.submit() }

  toggle() {
    this.#bootstrapModal.toggle()
    this.trigger('toggle')
  }

  show() {
    this.#bootstrapModal.show()
    this.trigger('show')
  }

  hide() {
    this.#bootstrapModal.hide()
    this.trigger('hide')
  }

  close() {
    this.trigger('close')
    this.destroy()
  }

  destroy() {
    this.#el.remove()
    this.#bootstrapModal.dispose()
    this.#el = undefined
    this.#bootstrapModal = undefined
    this.#options = undefined
    this.#eventHandlers = undefined
  }

  bind(event, callback, scope) {
    this.#eventHandlers.push([event, callback, scope])
    return this
  }

  unbind(event, callback) {
    event.split(' ')
      .forEach((event) => {
        const findFn = undefined === callback
          ? (h) => h[0] === event
          : (h) => h[0] === event && h[1] === callback
        let i = this.#eventHandlers.findIndex(findFn)
        while (i > -1) {
          this.#eventHandlers.splice(i, 1)
          i = this.#eventHandlers.findIndex(findFn)
        }
      })

    return this
  }

  trigger(event, ...args) {
    const eventHandlers = this.#eventHandlers
      .filter((h) => h[0] === event)

    const l = eventHandlers.length
    for (let i = 0; i < l; i++) {
      if (eventHandlers[i][1].apply(eventHandlers[i][2] || this, args) === false) {
        return
      }
    }
  }
}

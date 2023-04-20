import EventsTrait from './support/events-trait'
import { MimeToExtension } from '~resources/js/libs/uploader/functions'

class File {
  #el

  #data

  constructor(data) {
    this.#data = data
  }

  get path() { return this.#data.path }

  get type() { return this.#data.type }

  get src() { return this.#data.src }

  get name() { return this.#data.name }

  get mimeType() { return this.#data.mime_type }

  isFolder() { return this.#data.type === 'folder' }

  isImage() { return this.#data.type === 'file' && this.#data.mime_type && this.#data.mime_type.indexOf('image/') === 0 }

  isFile() { return this.#data.type === 'file' }

  get el() {
    if (this.#el) return this.#el

    let html = `<div class="fm-item ${this.type}" title="${this.name}">`
    html += `<div class="image-wrap ${this.isFolder() ? 'folder' : MimeToExtension(this.#data.mime_type)}">`
    if (this.isImage()) html += `<img class="image" src="${this.src}" />`
    html += '</div>'
    html += `<div class="name">${this.#data.name}</div>`
    html += '</div>'

    const el = $(html)
    const self = this

    el
      .click((e) => { e.stopPropagation() })
      .mousedown((e) => {
        if (!self.isSelected()) self.select(true)
      })
      .dblclick((e) => {
        self.trigger('choose')
        self.select()
        e.stopPropagation()
      })

    return this.#el = el
  }

  isSelected() { return this.el.hasClass('selected') }

  isHidden() { return this.el.is(':hidden') }

  rename(name) {
    this.#data.name = name
    this.el.find('div.name').html(name)
  }

  select(fireEvent) {
    this.el.addClass('selected')
    if (fireEvent) this.trigger('select')
  }

  deselect(fireEvent) {
    this.el.removeClass('selected')
    if (fireEvent) this.trigger('deselect')
  }

  show() { this.el.show() }

  hide() { this.el.hide() }

  destroy() {
    this.el.remove()
  }
}

Object.assign(File.prototype, EventsTrait)

export default File

function InputBox(params) {
  const wrap = $('<div class="shadow"></div>').appendTo(document.body)
  wrap.append(`<div class="input-box"><input type="text" placeholder="${params.placeholder || ''}" value="${params.value || ''}" /></div>`)
  wrap.show().find('input')
    .keydown(function (e) {
      e.stopPropagation()
      if (e.keyCode === 27) // esc
      { wrap.remove() } else if (e.keyCode === 13) $(this).blur()
    })
    .blur(function () {
      const v = $(this).val()
      if (v !== '' && v !== params.value) params.handler.call(wrap, v)
      wrap.remove()
    })
    .select()
}

class Toolbar {
  #manager

  #el

  constructor(manager) {
    this.#manager = manager
  }

  get el() {
    if (this.#el) return this.#el

    let html = '<div class="fm-toolbar">'
    html += '<label for="file-upload" class="file-upload">Загрузить<input type="file" multiple id="file-upload" /></label>'
    html += '<button class="btn-folder-add">Создать папку</button>'
    html += '<button class="btn-rename" style="display:none">Переименовать</button>'
    html += '<button class="btn-trash" style="display:none">Удалить</button>'
    // html += '<div class="spacer"></div>';
    html += '</div>'

    const self = this
    const el = $(html)

    el.find('input').change(function () {
      self.#manager.upload(this.files)
    })

    el.find('button.btn-folder-add').click(() => {
      InputBox({
        placeholder: 'Название папки',
        handler(name) { self.#manager.createFolder(name) },
      })
    })

    el.find('button.btn-rename').click(() => { this.rename() })

    el.find('button.btn-trash').click(() => { this.delete() })

    this.#manager.bind('selection-changed', function () {
      const selectedCount = this.getSelected().length
      el.find('button.btn-rename')[selectedCount === 1 ? 'show' : 'hide']()
      el.find('button.btn-trash')[selectedCount === 0 ? 'hide' : 'show']()
    })

    return this.#el = el
  }

  rename() {
    const file = this.#manager.container.lastSelected
    InputBox({
      value: file.name,
      handler: (name) => { this.#manager.rename(file, name) },
    })
  }

  delete() {
    if (confirm('Выделенные файлы будут безвозвратно удалены! Продолжить?')) this.#manager.deleteSelected()
  }

  resetUpload() {
    this.el.find('input').val('')
  }
}

export default Toolbar

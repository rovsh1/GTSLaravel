(() => {
  function buildItemsHtml($el) {
    return $el.find('option')
      .filter((i, o) => o.value !== '')
      .map((i, o) => `<div class="dropdown-item${o.selected ? ' active' : ''}" data-value="${o.value}">${o.text || '&nbsp;'}</div>`)
      .get()
      .join('')
  }

  function buildPopup($select) {
    let html = '<div class="dropdown-menu">'

    const $groups = $select.find('optgroup')
    if ($groups.length === 0) {
      html += buildItemsHtml($select)
    } else {
      $groups.each((i, group) => {
        html += '<div class="group">'
        html += `<div class="label">${group.getAttribute('label')}</div>`
        html += '<div class="items">'
        html += buildItemsHtml($(group))
        html += '</div>'
        html += '</div>'
      })
    }
    html += '</div>'
    return html
  }

  function buildHtml() {
    let html = '<div class="ui-multiselect">'
    // eslint-disable-next-line no-useless-concat
    html += '<div class="value form-control">' + '<div class="label">&nbsp;</div>' + '<div class="select"></div>' + '</div>'
    html += '</div>'
    return html
  }

  class Plugin {
    #el

    #popup

    #select

    #options

    #ondocumentclick

    constructor($select, options) {
      this.#select = $select
      this.#options = options
      this.#el = $(buildHtml($select))

      this.#el.find('div.value').click(() => {
        if (this.#el.hasClass('expanded')) {
          this.collapse()
        } else {
          this.expand()
        }
      })

      this.#el.find('div.select').click((e) => {
        e.stopPropagation()
        const optionsCount = this.options.length
        const selectedCount = this.selectedOptions.length
        if (optionsCount === selectedCount) {
          this.select([])
        } else {
          this.select(this.options.map((i, o) => o.value.toString()).get())
        }
      })

      this.update()
    }

    get el() { return this.#el }

    get popup() {
      if (this.#popup) {
        return this.#popup
      }

      // eslint-disable-next-line @typescript-eslint/no-this-alias
      const self = this
      // eslint-disable-next-line no-multi-assign
      const $popup = this.#popup = $(buildPopup(this.#select))
        .appendTo(this.#el)

      $popup.find('div.dropdown-item').click(function () {
        const value = $(this).data('value').toString()
        const values = self.value
        const i = values.findIndex((v) => v === value)
        if (i === -1) {
          values.push(value)
        } else {
          values.splice(i, 1)
        }
        self.select(values)
      })

      return $popup
    }

    get options() { return this.#select.find('option') }

    get selectedOptions() { return this.options.filter(':selected') }

    get value() { return this.selectedOptions.map((i, o) => o.value.toString()).get() }

    get disabled() { return this.#select.is(':disabled') }

    update() {
      if (this.disabled) {
        this.#el.find('div.value .label').html(this.#options.disabledText)
        this.collapse()
      } else {
        const optionsCount = this.options.length
        const selectedCount = this.selectedOptions.length
        const values = this.value
        const labelText = selectedCount === 0 ? 'Не выбрано' : `Выбрано ${selectedCount} из ${optionsCount}`
        const btnText = selectedCount === optionsCount ? 'Снять выделение' : 'Выбрать все'

        this.#el.find('div.value .select').html(btnText)
        this.#el.find('div.value .label').html(labelText)

        if (this.#popup) {
          this.popup.find('div.dropdown-item').each((i, item) => {
            item.classList[values.includes(item.getAttribute('data-value').toString()) ? 'add' : 'remove']('active')
          })
        }
      }
    }

    select(value) {
      // eslint-disable-next-line no-param-reassign
      if (!Array.isArray(value)) { value = [value] }

      this.options.each((i, o) => {
        // eslint-disable-next-line no-param-reassign
        o.selected = value.includes(o.value.toString())
      })

      this.#select.change()
    }

    enable() {
      this.#el.removeClass('disabled')
      this.#select.attr('disabled', false)
      this.update()
    }

    disable() {
      this.#select.attr('disabled', true)
      this.#el.addClass('disabled')
      this.update()
    }

    collapse() {
      this.#el.removeClass('expanded')
      this.popup.hide()
      $(document).unbind('click', this.#ondocumentclick)
      this.#ondocumentclick = undefined
    }

    expand() {
      this.#el.addClass('expanded')
      this.popup.show()
      this.#ondocumentclick = (e) => {
        if (!this.#el.is(e.target) && this.#el.find(e.target).length === 0) {
          this.collapse()
        }
      }
      $(document).click(this.#ondocumentclick)
    }
  }

  $.fn.multiselect = function (options) {
    return $(this).each(function () {
      if (!this.multiple) {
        return
      }

      if (this._multiselect) {
        const plugin = this._multiselect
        // eslint-disable-next-line default-case
        switch (options) {
          case 'enable':
            plugin.enable()
            break
          case 'disable':
            plugin.disable()
            break
          case 'update':
            plugin.update()
            break
        }
      } else {
        this._multiselect = new Plugin($(this), options)

        $(this)
          .change(() => { this._multiselect.update() })
          .hide()
          .attr('required', false)
          .after(this._multiselect.el)
      }
    })
  }
})()

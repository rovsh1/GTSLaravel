export default class Item {
  #tab: JQuery<HTMLElement>

  #el: JQuery<HTMLElement>

  #input: JQuery<HTMLElement>

  constructor(tab: JQuery<HTMLElement>, el: JQuery<HTMLElement>) {
    this.#tab = tab
    this.#el = el
    this.#input = $('<input type="checkbox" class="form-check-input"/>')
      .prependTo(el)

    const self = this
    el.find('div.item')
      .click(function () {
        self.toggle($(this)
          .data('permission'))
      })

    this.#input.change(() => {
      const flag = this.#input.is(':checked')
      if (flag) {
        this.$items.addClass('allowed')
        this.$items.find('input')
          .val(1)
        this.#el.addClass('active')
      } else {
        this.$items.removeClass('allowed')
        this.$items.find('input')
          .val(0)
        this.#el.removeClass('active')
      }
      this.#tab.update()
    })

    this.update()
  }

  get $items() {
    return this.#el.find('div.permissions>div')
  }

  get hasUnchecked() {
    return this.$items.filter(':not(.allowed)').length > 0
  }

  get hasChecked() {
    return this.$items.filter('.allowed').length > 0
  }

  toggle(permission: string) {
    const $item = this.$items.filter(`[data-permission="${permission}"]`)
    $item.toggleClass('allowed')
    $item.find('input')
      .val($item.hasClass('allowed') ? 1 : 0)
    this.update()
  }

  update() {
    this.#input.prop('checked', !this.hasUnchecked)
    this.#el[this.hasChecked ? 'addClass' : 'removeClass']('active')
    this.#tab.update()
  }
}

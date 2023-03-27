import Item from './item'

export default class Tab {
  #tab

  #items = []

  constructor($tab, $menu) {
    this.#tab = $tab
    // $tab.append('<i>0</i>');

    const self = this
    const items = []
    $menu.find('div.menu-item').each(function () {
      items.push(new Item(self, $(this)))
    })
    this.#items = items

    this.update()
  }

  update() {
    const n = this.#items.filter((item) => item.hasChecked).length
    if (n > 0) {
      this.#tab.addClass('allowed')
      // this.#tab.find('i').html(n).show();
    } else {
      this.#tab.removeClass('allowed')
      // this.#tab.find('i').hide();
    }
  }
}

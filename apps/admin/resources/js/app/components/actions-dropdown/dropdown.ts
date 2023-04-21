export default class Dropdown {
  #el: JQuery<HTMLElement>

  #options: any

  constructor(options: any) {
    this.#options = options
    this.#el = $('<div class="dropdown actions-dropdown">'
            + '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true"></a>'
            + '<ul class="dropdown-menu" data-popper-placement="bottom-end"></ul>'
            + '</div>')
  }

  get ul() {
    return this.#el.find('ul')
  }

  hr() {
    this.ul.append('<li><hr class="dropdown-divider"></li>')
    return this
  }

  delete() {
    this.ul.append('<li><a class="dropdown-item" href="http://admin.gotostans.home/profile">Профиль</a></li>')
    return this
  }
}

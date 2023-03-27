export default class Paginator {
  #el

  #params

  constructor(params) {
    this.step = 90
    this.page = 1
    this.#el = $('<div class="pagination">'
			+ '<div class="label">Страницы</div>'
			+ '<div class="pages"></div>'
			+ '</div>')
    this.#params = params
  }

  get el() { return this.#el }

  setCount(count) {
    this.pages = Math.ceil(count / this.step)

    if (count < this.step) {
      this.#el.hide()
      return
    }

    const self = this
    let html = ''

    const p = (i) => { html += `<div data-page="${i}" class="page${i === this.page ? ' current' : ''}">${i}</div>` }
    const dots = () => { html += '<div class="dots">...</div>' }

    let s = this.page - 3
    if (s < 1) s = 1

    let e = this.page + 3
    if (e > this.pages) e = this.pages

    if (s > 1) {
      p(1)
      dots()
    }

    for (let i = s; i <= e; i++) { p(i) }

    if (e < this.pages) {
      dots()
      p(this.pages)
    }
    // html += '<div class="label"> из ' + pages + '</div>';
    this.#el.find('div.pages').html(html).show()
    this.#el.find('div.page').click(function () { self.#params.select($(this).data('page')) })

    this.#el.show()
  }

  setPage(page) {
    if (this.page === page) return

    this.page = page

    this.#el.find('div.page').each(function () {
      if (+$(this).data('page') === page) $(this).addClass('current')
      else $(this).removeClass('current')
    })
  }
}

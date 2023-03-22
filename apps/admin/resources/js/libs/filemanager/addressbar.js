class AddressBar {
	#manager;
	#el;

	constructor(manager) {
		this.#manager = manager;
	}

	get el() {
		if (this.#el)
			return this.#el;

		let html = '<div class="fm-address-bar">';
		html += '<button class="btn-refresh" title="Обновить"></button>';
		html += '<button class="btn-up" title="Верхняя категория"></button>';
		html += '<div class="path"></div>';
		//html += '<div class="spacer"></div>';
		html += '<div class="filter">'
		html += '<button data-type="folder" class="btn-folder" title="Показать только папки"></button>';
		html += '<button data-type="file" class="btn-file" title="Показать только документы"></button>';
		html += '<button data-type="image" class="btn-image" title="Показать только изображения"></button>';
		//html += '<button data-type="archive" class="btn-archive"></button>';
		html += '</div>'
		html += '<div class="search">'
			+ '<input type="search" placeholder="Поиск" />'
			+ '</div>';
		html += '</div>';

		const self = this;
		const el = $(html);

		el.find('div.filter button').click(function () { self.#manager.filterByType($(this).hasClass('selected') ? null : $(this).data('type')); });
		el.find('button.btn-refresh').click(function () { self.#manager.refresh(); });
		el.find('button.btn-up').click(function () { self.#manager.goUp(); });
		el.find('input')
			.keyup(function (e) {
				if (e.keyCode === 27)
					this.value = '';
				else if (e.keyCode === 13)
					self.#manager.search(this.value);
				else
					self.#manager.search(this.value);
			});

		return this.#el = el;
	}

	get searchValue() { return this.#el.find('input').val(); }

	setPath(path) {
		const btnUp = this.el.find('button.btn-up');
		const nav = this.el.find('div.path');

		btnUp.attr('disabled', this.#manager.isHome());
		//nav.show();

		let temp = '';
		const a = path.split('/')
			.filter(p => p !== '')
			.map(p => {
				temp += temp ? ('/' + p) : p;
				return '<div data-path="' + temp + '" class="item">' + p + '</div>';
			});

		a.unshift('<div data-path="" class="item home" title="Корневой каталог"></div>');

		const self = this;
		nav.html(a.join('<div class="separator"></div>'));
		nav.find('div.item')
			.click(function () { self.#manager.loadPath($(this).data('path')); })
			.filter(':not(:last-child)')
			.droppable({
				drop: (e, ui) => { this.#manager.moveSelectedTo($(e.target).data('path')); }
			});
	}

	setFilter(type) {
		const f = this.el.find('div.filter');
		f.find('button.selected').removeClass('selected');
		if (type)
			f.find('button.btn-' + type).addClass('selected');
	}
}

export default AddressBar;

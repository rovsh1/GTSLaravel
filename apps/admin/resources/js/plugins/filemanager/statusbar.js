import Paginator from "./paginator";

class StatusBar {
	#manager;
	#paginator;
	#el;

	constructor(manager) {
		this.#manager = manager;
		this.#paginator = new Paginator({
			select: (page) => { this.#manager.loadPage(page); }
		});
	}

	get el() {
		if (this.#el)
			return this.#el;

		let html = '<div class="fm-status-bar">';
		html += '<div class="count"></div>';
		//html += '<div class="pagination"></div>';
		html += '<div class="selected"></div>';
		html += '<div class="spacer"></div>';
		html += '<div class="progressbar"></div>';
		html += '<nav class="view-mode">'
			+ '<button data-view="list" class="btn-list" title="Отображение элементов в виде списка"></button>'
			+ '<button data-view="grid" class="btn-grid" title="Отображение элементов в виде значков"></button>'
			+ '</nav>';
		html += '</div>';

		const self = this;
		const el = $(html);

		el.find('div.count').after(this.#paginator.el);

		el.find('nav.view-mode button').click(function () { self.#manager.setView($(this).data('view')); });

		return this.#el = el;
	}

	get step() { return this.#paginator.step; }

	get page() { return this.#paginator.page; }

	setCount(count) {
		this.#paginator.setCount(count);
		const countEl = this.el.find('div.count');
		countEl.html('Элементов: ' + count);
	}

	setSelectedCount(count) {
		const s = this.el.find('>div.selected');
		if (count > 0) {
			s.html('Выбрано: ' + count).show();
		} else
			s.hide();
	}

	setPage(page) {
		this.#paginator.setPage(page);
	}

	setView(view) {
		const nav = this.el.find('nav.view-mode');
		nav.find('button.selected').removeClass('selected');
		nav.find('button.btn-' + view).addClass('selected');
	}

	setProgress(html) {
		const p = this.el.find('div.progressbar');
		if (html)
			p.html(html).show();
		else
			p.hide();
	}
}

export default StatusBar;

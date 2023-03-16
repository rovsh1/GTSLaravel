import {defaultHandlers} from "./buttons-builder";

function processForm(modal, form) {
	form.submit(function (e) {
		e.preventDefault();
		if (false === modal.trigger('beforeSubmit', form)) {
			return;
		}

		modal.setLoading(true);
		const data = new FormData(this);
		const url = modal.get('url');

		Http.ajax({
			url: form.attr('action') || (is_string(url) ? url : url.url),
			method: 'post',
			data: data,
			cache: false, //dataType: 'json',
			contentType: false,
			processData: false,
			success: function (r) {
				if (typeof (r) === 'string') {
					modal.setHtml(r);
				}
				modal.setLoading(false);
				modal.trigger('submit', r);
			}
		});
	});

	if (modal.get('autofocus')) {
		form.find('input[type!="hidden"],textarea').eq(0).focus();
	}
}

export default function processHtml(modal, body) {
	body.find('a').click(function (e) {
		const url = $(this).data('url');
		if (url) {
			e.preventDefault();
			modal.load(url);
		}
	});

	body.find('button,a').click(function (e) {
		let action = $(this).data('action');
		if (action && defaultHandlers[action]) {
			e.preventDefault();
			defaultHandlers[action].call(modal);
		}
	});

	const firstEl = modal.body.firstChild;
	if (firstEl.data('title')) {
		modal.setTitle(firstEl.data('title'));
	}

	if (firstEl.data('cls')) {
		modal.set('cls', firstEl.data('cls'));
	}

	const form = modal.form;
	if (form.length && modal.get('processForm')) {
		processForm(modal, form);
	}
}

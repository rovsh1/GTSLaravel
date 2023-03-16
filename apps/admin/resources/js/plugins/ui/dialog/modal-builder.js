import {buttonsRenderer} from "./buttons-builder";

export const defaultOptions = {
	title: '',
	html: '',
	cls: '',
	autoLayout: false,
	closeAction: 'remove',
	processHtml: true,
	processForm: true,
	modal: true,
	autoclose: true,
	closable: true,
	draggable: false,
	buttons: []
};

export function createBootstrapModal($el, options) {
	return new bootstrap.Modal($el[0], {
		keyboard: false
	});
}

export function createElement(options) {
	let html = '<div class="modal fade" aria-hidden="true" tabIndex="-1"';
	if (options.id) {
		html += ' id="' + options.id + '"';
	}
	html += '>';
	html += '<div class="modal-dialog modal-dialog-centered">';
	html += '<div class="modal-content">';

	html += '<div class="modal-header">';
	html += '<h5 class="modal-title">' + options.title + '</h5>';
	html += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
	html += '</div>';

	html += '<div class="modal-body"></div>';

	html += '<div class="modal-footer"></div>';

	html += '</div>';
	html += '</div>';
	html += '</div>';

	return $(html).appendTo(document.body);
}

export function bindEvents(modal, options) {
	const bindEvents = ['load', 'beforeSubmit', 'submit', 'update', 'show', 'hide', 'close'];
	bindEvents
		.filter(n => is_function(options[n]))
		.forEach(n => {
			modal.bind(n, options[n]);
			delete options[n];
		});
}

export function boot(modal, options) {
	if (options.html) {
		modal.setHtml(options.html);
	}

	if (options.buttons) {
		buttonsRenderer(modal, options.buttons, modal.footer);
	}

	if (options.url) {
		modal.load(options.url);
	}
}
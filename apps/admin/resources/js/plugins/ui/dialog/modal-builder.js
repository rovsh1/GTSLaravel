import {Modal as BootstrapModal} from "bootstrap";
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

export function createBootstrapModal(modal, $el, options) {
	const bsModal = new BootstrapModal($el[0], {
		keyboard: false
	});

	// bsModal.addEventListener('hide.bs.modal', function (event) {
	// 	modal.hide();
	// });

	return bsModal;
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
	html += '<button type="button" class="btn-close" aria-label="Close"></button>';
	html += '</div>';

	html += '<div class="modal-body"></div>';

	html += '<div class="modal-footer" style="display: none;"></div>';

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
	modal.header.find('button.btn-close').click(e => {
		modal.close();
	});

	if (options.html) {
		modal.setHtml(options.html);
	}

	if (options.buttons && options.buttons.length > 0) {
		buttonsRenderer(modal, options.buttons, modal.footer.show());
	}

	if (options.url) {
		modal.load(options.url);
	}

	if (!options.hidden) {
		modal.show();
	}
}
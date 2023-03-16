import Modal from "./modal";

export function WindowDialog(options) {
	const modal = new Modal(options);
	modal.show();
	return modal;
}

export function MessageConfirm(message, title, fn) {
	if (typeof message === 'string') {
		message = {
			title: title,
			html: message,
			cls: 'window-message window-confirm'
		};
	}

	return new Modal(Object.assign({
		title: '@lang(Message)',
		cls: 'window-message',
		buttons: ['ok', 'cancel']
	}, message));
}

export function MessageBox(message, title, type) {
	if (typeof message === 'string') {
		message = {
			title: title || '@lang(Message)',
			html: message,
			cls: 'window-message' + (type ? ' window-' + type : '')
		};
	}

	return new Modal(Object.assign({
		title: '@lang(Message)',
		cls: 'window-message',
		buttons: ['ok']
	}, message));
}

Object.assign(window, {
	WindowDefaults: {},
	WindowDialog: WindowDialog,
	MessageConfirm: MessageConfirm,
	MessageBox: MessageBox
});
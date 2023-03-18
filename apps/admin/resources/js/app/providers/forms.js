import {MessageConfirm} from "../../plugins/ui/dialog/helpers";

function bootDeleteButtons() {
	$('button.btn-delete')
		.filter('[data-form-action="delete"]')
		.click(function (e) {
			const url = $(this).data('url');
			if (!url) {
				return;
			}

			e.preventDefault();

			const $btn = $(this);
			const $form = $('<form method="post" action="' + url + '">'
				+ '<p>Удалить запись?</p>'
				+ '<input type="hidden" name="_method" value="delete"/>'
				+ '</form>');

			WindowDialog({
				title: 'Подтверждение',
				html: $form,
				buttons: [{
					text: 'Подтвердить',
					cls: 'btn btn-primary',
					handler: 'submit'
				}, 'cancel'],
				submit: () => { $btn.attr('disabled', true); }
			});
		});
}

function bootMultiselect() {
	$('select[multiple]').multiselect({
		popupCls: 'dropdown-menu'
	});
}

export default function bootForms() {
	bootDeleteButtons();
	bootMultiselect();
}
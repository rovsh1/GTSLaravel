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

			MessageConfirm('Удалить запись?', 'Подтверждение', () => {
				$(this).attr('disabled', true);

				const $form = $('<form method="post" style="display: none;" action="' + url + '">'
					+ '<input type="hidden" name="_method" value="delete"/>'
					+ '</form>')
					.appendTo(document.body);

				$form.submit();
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
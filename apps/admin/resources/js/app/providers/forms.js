function bootDeleteButtons() {
	$('button.btn-delete')
		.filter('[data-form-action="delete"]')
		.click(function (e) {
			const url = $(this).data('url');
			if (!url) {
				return;
			}

			e.preventDefault();

			$(this).attr('disabled', true);

			const $form = $('<form method="post" style="display: none;" action="' + url + '">'
				+ '<input type="hidden" name="_method" value="delete"/>'
				+ '</form>')
				.appendTo(document.body);

			$form.submit();
		});
}

export default function bootForms() {
	bootDeleteButtons();
}
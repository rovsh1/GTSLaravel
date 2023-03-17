export default function bootGrid() {
	const $btn = $('#btn-grid-filters');
	const $popup = $('#grid-filters-popup');
	const close = (e) => {
		if (!$popup.is(e.target) && $popup.find(e.target).length === 0) {
			$popup.hide();
			$(document).unbind('click', close);
		}
	};

	$btn.click(e => {
		e.preventDefault();
		if (!$popup.is(':hidden')) {
			return;
		}

		$popup.fadeIn(200);
		$(document).click(close);
		e.stopPropagation();
	});

	$popup.find('button[type="reset"]').click(e => {
		e.preventDefault();

		$popup.find('input,select').val('');
	});
}
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
				submit: () => {
					$btn.attr('disabled', true);
				}
			});
		});
}

function bootMultiselect() {
	$('select[multiple]').multiselect({
		popupCls: 'dropdown-menu'
	});
}

function bootDateRangePicker() {
	$('.daterange').daterangepicker({
		autoApply: true,
		ranges: {
			'Сегодня': [moment(), moment()],
			'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
			'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
			'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
			'Прошлый месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		alwaysShowCalendars: true,
		autoUpdateInput: false,
		opens: 'left',
		drops: 'auto',
		showCustomRangeLabel: false,
		locale: {
			cancelLabel: 'Clear',
			format: 'DD.MM.YYYY',
			daysOfWeek: [
				'Вс',
				'Пн',
				'Вт',
				'Ср',
				'Чт',
				'Пт',
				'Сб',
			],
			monthNames: [
				'Январь',
				'Февраль',
				'Март',
				'Апрель',
				'Май',
				'Июнь',
				'Июль',
				'Август',
				'Сентябрь',
				'Октябрь',
				'Ноябрь',
				'Декабрь'
			],
		}
	});

	$('.daterange')
		.on('cancel.daterangepicker', (event, picker) => {
			$(event.target).val('');
		})
		.on('apply.daterangepicker', (event, picker) => {
			$(event.target).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
		});

}

function bootFileFields() {
	$('div.field-file div.thumb div.btn-remove').click(function (e) {
		e.preventDefault();
		const thumb = $(this).parent();

		MessageConfirm('Подверждение удаления', 'Файл будет удален без возможности восстановления, продолжить?', () => {
			thumb.addClass('loading');

			$.ajax({
				url: $(this).data('url'),
				method: 'delete',
				success: (r) => {
					const wrap = thumb.parent();
					thumb.remove();
					if (wrap.find('>div').length === 0) {
						wrap.remove();
					}
				}
			});
		});
	});
}

export default function bootForms() {
	bootDeleteButtons();
	bootMultiselect();
	bootDateRangePicker();
	bootFileFields();
}

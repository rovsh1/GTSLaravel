function editableContactRow(route, $tr) {
	const id = $tr.data('id');
	const html = '<td class="column-actions">'
		+ '<div class="grid-actions">'
		+ '<div class="icon">more_vert</div>'
		+ '<div class="dropdown">'
		+ '<button class="btn-delete" data-url="' + route + '/' + id + '">Удалить</button>'
		+ '<hr>'
		+ '<button class="btn-edit" data-url="' + route + '/' + id + '/edit">Изменить</button>'
		+ '</div>'
		+ '</div>'
		+ '</td>';
	const $actionsTd = $(html).appendTo($tr);

	$actionsTd.find('button.btn-delete').deleteButton();

	$actionsTd.find('button.btn-edit')
		.click(function () {
			WindowDialog({
				url: $(this).data('url'),
				title: 'Изменить контакт',
				buttons: ['submit', 'cancel']
			});
		});
}

$.fn.cardContacts = function (options) {
	const $el = $(this);
	const route = $el.data('route');
	const isEditable = !!route;

	$el.find('button.btn-add').click(function (e) {
		e.preventDefault();
		WindowDialog({
			url: $(this).data('url') || (location.pathname + '/contacts/create'),
			title: 'Контакты',
			buttons: ['submit', 'cancel']
		});
	});

	if (isEditable) {
		$el.find('tr').each((i, tr) => { editableContactRow(route, $(tr)); });
	}
};
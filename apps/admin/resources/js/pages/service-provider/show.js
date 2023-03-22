import "../main";
import ContactsCard from "../../app/components/contacts-card/card";

$(document).ready(function () {
	$('#btn-add-contact').click(function (e) {
		e.preventDefault();
		WindowDialog({
			url: $(this).data('url') || (location.pathname + '/contacts/create'),
			title: 'Контакты',
			buttons: ['submit', 'cancel']
		});
	});

	$('#card-contacts')
		.find('button.btn-edit')
		.click(function () {
			WindowDialog({
				url: $(this).data('url'),
				title: 'Изменить контакт',
				buttons: ['submit', 'cancel']
			});
		});
	// const card = new ContactsCard($('#card-contacts'));
	// card.boot();
});
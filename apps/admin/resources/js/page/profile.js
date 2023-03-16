import './main';

$(document).ready(function () {
	const container = $('#profile-settings');
	const settingsUrl = '/profile/';

	container.find('div.block-row').click(function () {
		const action = $(this).data('action');
		if (!action) {
			return;
		}

		switch (action) {
			case 'photo':
				WindowDialog({
					url: settingsUrl + action,
					content: function () {
						const fileInput = this.el.find('input[type="file"]');
						this.el.find('button').click(function () { fileInput.click(); });
						fileInput.change(function () { $(this.form).submit(); });
						//const img = $('<div class="">' + user_avatar(app.user) + '</div>');
					}
				});
				break;
			default:
				WindowDialog({url: settingsUrl + action});
		}
	});
});

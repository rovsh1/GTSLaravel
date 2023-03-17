function processResponse(response) {
	if (!response.action) {
		return;
	}

	switch (response.action) {
		case 'redirect':
			return location.redirect(response.url);
		case 'reload':
			return location.reload();
	}
}

export function ajax(modal, params, callback) {
	params.error = function (xhr) {
		modal.setHtml(xhr.responseText);
		modal.setLoading(false);
	};

	params.success = function (r, code, xhr) {
		if (xhr.responseJSON) {
			processResponse(r);
			callback(xhr.responseJSON);
		} else {
			modal.setHtml(r);
			modal.setLoading(false);
			callback(r);
		}
	};

	$.ajax(params);
}

export default function load(modal, params) {
	modal.setLoading(true);

	if (typeof params === 'string') {
		params = {url: params};
	}

	ajax(modal, params, r => {
		modal.trigger('load');
	});
}
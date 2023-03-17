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
	params.error = function (r) {
		modal.setHtml('Loading failed');
		modal.setLoading(false);
	};

	params.success = function (r) {
		if (typeof (r) === 'string') {
			modal.setHtml(r);
			modal.setLoading(false);
		} else {
			processResponse(r);
		}
		callback(r);
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
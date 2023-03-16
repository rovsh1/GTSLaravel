export default function load(modal, params) {
	modal.setLoading(true);

	if (typeof params === 'string') {
		params = {url: params};
	}

	params.success = function (html) {
		modal.setHtml(html);
		modal.setLoading(false);
		modal.trigger('load');
	};
	params.error = function () {
		modal.setHtml('Loading failed');
		modal.setLoading(false);
	};

	$.ajax(params);
}
import EventsTrait from "gsv-pkg/support/events-trait"

const boundary = "xxxxxxxxx";

class Reader {

	constructor(params) {

	}

	sendFormData(formData, url) {
		let uploaded = false;
		const self = this;
		const xhr = new XMLHttpRequest();

		xhr.upload.addEventListener("progress", function (e) {
			if (e.lengthComputable) {
				const progress = (e.loaded * 100) / e.total;
				self.trigger('progress', Math.round(progress));
			}
		}, false);

		xhr.upload.addEventListener("load", function () {
			uploaded = true;
			self.trigger('progress', 100);
		}, false);

		xhr.upload.addEventListener("error", function () { self.trigger('error'); }, false);

		xhr.onreadystatechange = function () {
			if (this.readyState !== 4)
				return;

			if (this.status == 200) {
				if (uploaded)
					self.trigger('complete', JSON.parse(this.responseText));
				else
					self.trigger('error', {
						code: this.status,
						text: this.responseText
					});
			} else {
				const data = (() => {
					try {
						return JSON.parse(this.responseText);
					} catch (e) {
						return {};
					}
				})();

				self.trigger('error', {
					code: this.status,
					message: data.message,
					text: 'HTTP response code is not OK (' + this.status + ')'
				});
			}
		};

		xhr.open("POST", url);
		xhr.setRequestHeader('Accept', 'application/json');
		xhr.send(formData);
	}

	upload(files, params) {
		const self = this;

		if (files.length) {
			const l = files.length;
			let n = l;
			for (let i = 0; i < l; i++) {
				const reader = new FileReader();
				reader.onload = function (e) {
					if (n === 1) {
						self.trigger('ready');

						const formData = new FormData();

						if (params.data) {
							for (let i in params.data) {
								formData.append(i, params.data[i]);
							}
						}

						for (let i = 0; i < l; i++) {
							formData.append('file[]', files[i]);
						}

						self.sendFormData(formData, params.url);
					}
					n--;
				}
				reader.readAsBinaryString(files[i]);
			}
		} else {
			const reader = new FileReader();
			reader.onload = function (e) {
				self.trigger('ready', e);

				const formData = new FormData();
				formData.append('file', files);

				self.sendFormData(formData, params.url);
			}
			reader.readAsBinaryString(files);
		}
	}
}

Object.assign(Reader.prototype, EventsTrait);

export default Reader;

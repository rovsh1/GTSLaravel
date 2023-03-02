import Reader from "./reader";
import {
	isImage,
	getMimeImage,
	MimeToExtension
} from "./functions"

export default class Thumb {
	$el;
	$file;
	$data = {};
	$inputName;

	constructor(plugin, inputName) {
		this.plugin = plugin;
		this.$inputName = inputName;
	}

	get el() {
		if (this.$el)
			return this.$el;

		const self = this;
		const el = $('<div class="thumb">'
			+ '<div class="btn-remove"></div>'
			+ '</div>');

		el.find('div.btn-remove').click(function () { self.delete(); });

		return this.$el = el;
	}

	get id() { return this.$data.id; }

	get(name) { return this.$data[name]; }

	renderTo(el) {
		el.append(this.el);
		return this;
	}

	data(data) {
		this.$data = data;

		const el = this.el;
		let html = '';

		html += '<a href="/file/' + data.guid + '" target="_blank" title="' + data.name + '">';

		if (isImage(data.mime_type)) {
			el.addClass('image');
			html += '<img src="/image/' + data.guid + '" />';
		} else
			html += '<img src="' + getMimeImage(data.mime_type) + '" />';

		html += '</a>';

		el.append(html);
	}

	setImage(src) {
		this.el.find('img').attr('src', src);
	}

	read(file) {
		this.el.addClass('upload');

		this.setImage(getMimeImage(file.type));

		if (isImage(file.type)) {
			const reader = new FileReader();
			reader.onload = function (e) { this.setImage(e.target.result); };
			reader.readAsDataURL(file);
		}
	}

	upload(file) {
		const self = this;
		const el = this.el;
		const inputName = this.$inputName;
		const reader = new Reader();

		el.addClass('loading');//.append('<i class="icon-upload"></i>');
		this.setImage(getMimeImage(file.type));

		reader
			.bind('ready', (e) => {
				//console.log(e)
				/*if (isImage(file))
					self.setImage(e.target.result);
				else
					this.setImage(getTypeImage(file));
				el.append('<img src="">')*/
			})
			.bind('start', () => {})
			.bind('progress', (p) => { /*console.log(p)*/ })
			.bind('complete', (data) => {
				self.$data = data;
				let html = '<a href="' + data.src + '" target="_blank">';
				if (isImage(file.type)) {
					el.addClass('image');
					html += '<img src="' + data.src + '" />';
				} else
					html += '<img src="' + getMimeImage(file.type) + '" />';
				html += '</a>';
				html += '<input type="hidden" name="' + inputName + '[tmp_name]" value="' + data.filename + '">';
				html += '<input type="hidden" name="' + inputName + '[name]" value="' + data.name + '">';
				el.append(html).removeClass('loading');
			})
			.bind('error', (error) => {
				alert(error.message);
				el.removeClass('loading').addClass('error');
				this.plugin.remove(this);
			})
			.upload(file, {url: '/file/upload'});
	}

	getById(fileId) {
		const el = this.el;
		el.addClass('loading');
		Http.getJSON('/file/' + fileId + '/data', function (data) {

			el.removeClass('loading');
		});
	}

	remove() {
		this.el.remove();
		if (this.isNew() && this.get('filename'))
			Http.post('/tmp/' + this.get('filename') + '/unlink');
	}

	delete() {
		if (this.isNew()) {

		} else {
			if (!confirm('Файл будет безвозвратно удален. Продолжить?'))
				return;
			this.el.addClass('removed');
			this.el.addClass('loading');
			//this.el.append('<input type="hidden" name="" value="' + this.id + '" />');
			Http.post('/file/' + this.id + '/delete');
		}
		this.plugin.remove(this);
	}

	isNew() { return !this.id; }
}

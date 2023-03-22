import Thumb from "./thumb"

let ID = 0;

export default class Plugin {
    #el;
    #input;
    #thumbs = [];
    #options;

    constructor(options) {
        this.#options = Object.assign({
            multiple: false,
            accept: /.*/,
            dragDrop: true,
            delete: function () {}
        }, options);
        this.#input = options.input;
    }

    remove(thumb) {
        const i = this.#thumbs.findIndex(t => t === thumb);
        if (-1 === i)
            return;

        this.#thumbs.splice(i, 1);
        thumb.remove();
    }

    appendFile(data) {
        const thumb = new Thumb(this, this.#input.attr('name'));
        thumb
            //.bind('remove', function () { self.remove(this); })
            .renderTo(this.el)
            .data(data);
        this.#thumbs.push(thumb);
    }

    get el() {
        if (this.#el)
            return this.#el;

        const self = this;
        const input = this.#input;
        const multiple = self.#options.multiple;
        const inputName = input.attr('name');
        const el = $('<div class="ui-file-upload">'
            + '<div class="handler" title="Загрузить файл">'
            + '<i class="icon-upload"></i>'
            + '</div>'
            + '</div>');

        input.removeAttr('name');

        const fileFactory = file => {
            const thumb = new Thumb(this, multiple ? inputName + '[' + (ID++) + ']' : inputName);
            thumb
                .renderTo(el)
                .upload(file);

            return thumb;
        };
        const appendFiles = files => {
            if (!multiple) {
                self.#thumbs.forEach(thumb => { thumb.remove(); });
                self.#thumbs = [];
            }

            for (let i = 0; i < files.length; i++) {
                self.#thumbs.push(fileFactory(files[i]));
            }
        };

        el.find('div.handler')
            .click(function (e) { input.click(); })
            .append(input);

        input
            .click(function (e) { e.stopPropagation(); })
            .change(function () {
                appendFiles(this.files);
                input.val('');
            });

        el.bind({
            dragenter: function () {
                $(this).addClass('drag');
                return false;
            },
            dragover: function () { return false; },
            dragleave: function () {
                $(this).removeClass('drag');
                return false;
            },
            drop: function (e) {
                const dt = e.originalEvent.dataTransfer;
                appendFiles(dt.files);
                return false;
            }
        });

        this.#el = el;

        if (this.#options.file)
            this.appendFile(this.#options.file);

        return el;
    }
}

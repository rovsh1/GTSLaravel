const boundary = "xxxxxxxxx";

export default class Reader {
    #eventHandlers = [];

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

    bind(event, callback, scope) {
        this.#eventHandlers.push([event, callback, scope]);
        return this;
    }

    unbind(event, callback) {
        if (undefined === this.#eventHandlers)
            return this;

        event.split(' ')
            .forEach((event) => {
                const findFn = undefined === callback
                    ? h => h[0] === event
                    : h => h[0] === event && h[1] === callback;
                let i = this.#eventHandlers.findIndex(findFn);
                while (i > -1) {
                    this.#eventHandlers.splice(i, 1);
                    i = this.#eventHandlers.findIndex(findFn);
                }
            });

        return this;
    }

    trigger(event, ...args) {
        const eventHandlers = this.#eventHandlers
            .filter(h => h[0] === event);

        const l = eventHandlers.length;
        for (let i = 0; i < l; i++) {
            if (false === eventHandlers[i][1].apply(eventHandlers[i][2] || this, args))
                return;
        }
    }
}

import EventsTrait from "~resources/js/support/events-trait";
import { loadEditor } from "./loader"
import { getDefaultOptions } from "./default"
import { getFileFromManifest } from '~resources/js/support/build-manifest'

export default class HtmlEditor {
    #ready = false;
    #extraPlugins = '';
    #params;
    #editor;

    constructor(selector, params) {
        this.#params = params || {};
        this.#params.selector = selector;
    }

    get editor() {
        return this.#editor;
    }

    isReady() {
        return this.#ready;
    }

    set(param, value) {
        this.#params[param] = value;
        return this;
    }

    params(params) {
        if (!params)
            return this;

        Object.assign(this.#params, params);

        if (params.appvariables)
            this.#extraPlugins += ' appvariables';

        if (params.mastergallery)
            this.#extraPlugins += ' mastergallery';

        return this;
    }

    height(height) {
        return this.set('height', height);
    }

    bodyClass(cls) {
        return this.set('body_class', cls);
    }

    contentCss(url) {
        return this.set('content_css', url);
    }

    addPlugin(plugin, data) {
        this.#extraPlugins += ' ' + plugin;
        if (data)
            this.#params[plugin] = data;
        return this;
    }

    show() {
        if (this.#ready)
            this.#editor.show();
        else
            this.#params.hidden = false;
    }

    hide() {
        if (this.#ready)
            this.#editor.hide();
        else
            this.#params.hidden = true;
    }

    async init() {
        const self = this;
        const tinymce = await loadEditor();

        if (this.#extraPlugins)
            this.#params.plugins[2] += this.#extraPlugins;

        const defaultOptions = getDefaultOptions({
            contentCSSPath: await getFileFromManifest('resources/js/support/tinymce/tinymce-content.scss')
        })

        tinymce.init(Object.assign(defaultOptions, this.#params, {
            setup: function (editor) {
                self.#editor = editor;
                editor.on('init', function () {
                    self.trigger('ready');
                    self.#ready = true;
                    if (self.#params.hidden)
                        editor.hide();
                });
                editor.on('Change', function () {
                    editor.save();
                    $(editor.getElement()).change();
                });
            }
        }));
    }
}

Object.assign(HtmlEditor.prototype, EventsTrait);

export default class Layout {

    getMeta(selector) {
        const meta = document.head.querySelector(selector);
        return meta ? meta.content : null;
    }

    getMetaName(name) { return this.getMeta('meta[name="' + name + '"]'); }

}

import List from "./list";
import Item from "./item";

export default class Control {
    #el;
    #selectedList;
    #availableList;
    #items;

    constructor(items) {
        this.#items = items.map(r => new Item(r));
        this.#selectedList = new List();
        this.#availableList = new List();
        this.#items.forEach(item => {
            if (item.hasAnyPermission())
                this.#selectedList.add(item);
            else
                this.#availableList.add(item);
        });
    }

    get el() {
        if (this.#el) return this.#el;

        const el = $('<div class="permissions-control">'
            + '<div class="list-wrapper list-selected"></div>'
            + '<div class="controls"></div>'
            + '<div class="list-wrapper list-available"></div>'
            + '</div>');

        el.find('>div.list-selected').append(this.#selectedList.el);
        el.find('>div.list-available').append(this.#availableList.el);

        this.#el = el;

        // this.update();

        return el;
    }

    filter(fn) {
        this.#items.forEach(r => {
            if (fn(r)) r.show(); else r.hide();
        });
    }

    // update() {
    //     if (!this.#el) return;
    //     this.#availableList.reset();
    //     this.#selectedList.reset();
    // }
}

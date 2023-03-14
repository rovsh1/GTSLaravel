export default class List {
    #el;
    #items = [];

    get el() {
        if (this.#el)
            return this.#el;

        const el = $('<div class="permissions-list">'
            + '</div>');

        return this.#el = el;
    }

    add(item) {
        this.#items.push(item);
        this.el.append(item.el);
    }

    remove(item) {}

    has(item) { return this.#items.find(); }
}

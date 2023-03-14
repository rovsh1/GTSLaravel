const icons = {
    read: 'visibility',
    create: 'add',
    update: 'edit',
    delete: 'delete',
    'auth as': 'person'
};

function icon(key) {
    return '<i class="icon" data-permission="' + key + '">' + icons[key] + '</i>';
}

export default class Item {
    #el;
    #data;
    #change;
    #rules = {};

    constructor(data) {
        this.#data = data;
    }

    get key() { return this.#data.key; }

    get name() { return this.#data.name; }

    get category() { return this.#data.category; }

    get el() {
        if (this.#el)
            return this.#el;

        const permissions = this.#data.permissions
            .map(p => icon(p))
            .join('');

        const el = $('<div class="prototype-item">'
            + '<div class="permissions">' + permissions + '</div>'
            + '<div class="title">' + this.name + '</div>'
            + '</div>');

        el.find('i').click((e, a) => {
            console.log(e)
            e.stopPropagation();
            const i = $(e.target).data('permission');
            this.#rules[i] = !this.#rules[i];
            this.#change.call(this);
        });

        el.click(e => {
            //this.#change.call(this);
        });

        return this.#el = el;
    }

    hasAnyPermission() {
        for (let i in this.#rules) {
            if (this.#rules[i]) return true;
        }
        return false;
    }

    show() {
        this.el.show();
    }

    hide() {
        this.el.hide();
    }
}

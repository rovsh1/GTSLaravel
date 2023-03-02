import File from "./file"

function typeFilter(file, type) {
    switch (type) {
        case 'image':
            return file.isFolder() || file.isImage();
        case 'folder':
            return file.isFolder();
        case 'file':
            return file.isFolder() || (file.isFile() && !file.isImage());
        default:
            return true;
    }
}

function termFilter(file, term) {
    return !term || -1 !== file.name.indexOf(term);
}

let dropDisabled = true;

class Container {
    #manager;
    #el;
    #relativePath;
    #files = [];

    constructor(manager) {
        this.#manager = manager;
    }

    get el() {
        if (this.#el)
            return this.#el;

        const self = this;
        const el = $('<div class="fm-container"><div class="files-wrap"></div></div>');

        el.bind({
            click: function () { self.#manager.deselectAll(); },
            dragenter: function () {
                if (dropDisabled)
                    return false;
                el.addClass('drag');
                return false;
            },
            dragover: function () { return false; },
            dragleave: (e) => {
                if (el.find(e.target).length === 0)
                    el.removeClass('drag');
                return false;
            },
            drop: function (e) {
                if (dropDisabled)
                    return false;
                el.removeClass('drag');
                const dt = e.originalEvent.dataTransfer;
                //self.#manager.upload(dt.files);
                return false;
            }
        });

        $(window)
            .keydown((e) => {
                if (e.target.nodeName === 'INPUT' || e.target.nodeName === 'TEXTAREA')
                    return;

                if (e.ctrlKey)
                    self.multipleMode = true;
                else if (e.shiftKey)
                    self.rangeMode = true;

                switch (e.key) {
                    case 'F5':
                        e.preventDefault();
                        this.#manager.refresh();
                        break;
                    case 'F2':
                        e.preventDefault();
                        if (!this.lastSelected)
                            break;
                        return this.#manager.toolbar.rename();
                    case 'Delete':
                        e.preventDefault();
                        if (!this.lastSelected)
                            break;
                        return this.#manager.toolbar.delete();
                    case 'ArrowRight':
                        return this.selectNext();
                    case 'ArrowLeft':
                        return this.selectPrev();
                    case 'ArrowUp':
                        return this.selectUp();
                    case 'ArrowDown':
                        return this.selectDown();
                    case 'Enter':
                        e.preventDefault();
                        return this.lastSelected ? this.choose(this.lastSelected) : null;
                    case 'Backspace':
                        e.preventDefault();
                        return this.#manager.goUp();
                }
            })
            .keyup(function (e) {
                if (!e.ctrlKey)
                    self.multipleMode = false;
                if (!e.shiftKey)
                    self.rangeMode = false;
            });

        return this.#el = el;
    }

    setRelativePath(path) {
        this.#relativePath = path;
    }

    setView(view) { this.el.attr('class', 'fm-container view-' + view); }

    setFiles(files) {
        this.reset();

        const self = this;
        const el = this.#el.find('div.files-wrap');
        const onChoose = function () { self.choose(this); };
        const onSelect = function (e) {
            const file = this;
            if (self.rangeMode && self.lastSelected) {
                const fi = self.#files.indexOf(file);
                const li = self.#files.indexOf(self.lastSelected);
                const l = self.#files.length;
                const s = (fi > li ? li : fi);
                const e = fi > li ? fi : li;
                for (let i = 0; i < l; i++) {
                    if (i < s || i > e)
                        self.#files[i].deselect();
                    else
                        self.#files[i].select();
                }
            } else if (!self.multipleMode) {
                self.#files
                    .filter(f => f !== file)
                    .filter(f => f.isSelected())
                    .forEach(f => { f.deselect(); });
                self.lastSelected = file;
            } else
                self.lastSelected = file;

            self.#manager.trigger('selection-changed');
        };
        const onDeselect = function () {
            if (self.lastSelected === this)
                self.lastSelected = null;

            self.#manager.trigger('selection-changed');
        };
        const onDragStart = (e, ui) => {
            dropDisabled = true;
            document.body.classList.add('dragging');
            this.getSelected().forEach(file => file.el.css('opacity', '.5'));
        };
        const onDragEnd = (e, ui) => {
            dropDisabled = false;
            document.body.classList.remove('dragging');
            ui.helper.css({opacity: '', left: '', top: ''});
            this.getSelected().forEach(file => file.el.css('opacity', ''));
        };
        const onDrop = (e, ui) => {
            const folder = this.#files.find(file => file.el[0] === e.target);
            const path = folder.path + '/' + folder.name;
            if (confirm('Переместить выбранную папку в "' + path + '"?'))
                this.#manager.moveSelectedTo(path);
        };
        const fileFactory = function (f) {
            f.src = f.url;//self.#relativePath + '/' + f.name;
            const file = new File(f);
            file
                .bind('choose', onChoose)
                .bind('select', onSelect)
                .bind('deselect', onDeselect);
            el.append(file.el);
            self.#files.push(file);
            if (!typeFilter(file, self.filterType) || !termFilter(self.filterTerm))
                file.hide();

            file.el.draggable({
                containment: 'body',
                //stack: '.fm-item',
                zIndex: 999,
                opacity: .5,
                start: onDragStart,
                stop: onDragEnd,
            });

            if (file.isFolder())
                file.el.droppable({
                    drop: onDrop
                });
        };
        /*const sort = function (a, b) {
            if (a.name === b.name)
                return 0;
            return a.name > b.name ? 1 : -1;
        };*/

        files
            //.filter(f => f.type === 'folder')
            //.sort(sort)
            .forEach(fileFactory);

        /*files
            //.filter(f => f.type === 'file')
            //.sort(sort)
            .forEach(fileFactory);*/
    }

    filterByType(type) {
        this.filterType = type;
        this.#files.forEach(f => {
            if (typeFilter(f, type))
                f.show();
            else
                f.hide();
        });
    }

    search(term) {
        this.filterTerm = term;
        this.#files.forEach(f => {
            if (termFilter(f, term))
                f.show();
            else
                f.hide();
        });
    }

    getSelected() { return this.#files.filter(f => f.isSelected()); }

    choose(file) {
        if (file.isFolder())
            this.#manager.openPath(file.name);
        else {
            this.#files
                .filter(f => f !== file)
                .filter(f => f.isSelected())
                .forEach(f => { f.deselect(); });
            this.#manager.trigger('choose', file);

            this.#manager.trigger('selection-changed');
        }
    }

    select(file) {
        this.deselectAll();
        this.lastSelected = file;
        this.lastSelected.select();
        this.#manager.trigger('selection-changed');
    }

    selectNext() {
        const files = this.#files.filter(f => !f.isHidden());
        if (files.length === 0)
            return;

        let i = this.lastSelected ? files.indexOf(this.lastSelected) + 1 : 0;
        if (i >= files.length)
            i = 0;

        this.select(files[i]);
    }

    selectPrev() {
        const files = this.#files.filter(f => !f.isHidden());
        if (files.length === 0)
            return;

        let i = (this.lastSelected ? files.indexOf(this.lastSelected) : files.length) - 1;
        if (i < 0)
            i = files.length - 1;

        this.select(files[i]);
    }

    selectUp() {
        const files = this.#files.filter(f => !f.isHidden());
        if (files.length === 0)
            return;

        if (this.lastSelected) {
            let i = files.indexOf(this.lastSelected);
            const wrap = this.#el.find('div.files-wrap');
            const W = wrap.outerWidth();
            const w = files[0].el.outerWidth(true);
            const cols = Math.floor(W / w);
            const l = files.length;
            if (l <= cols)
                return;

            const rows = Math.ceil(l / cols);
            let row = Math.floor(i / cols);
            const col = i % cols;
            row = row === 0 ? (rows - 1) : (row - 1);
            i = row * cols + col;

            this.select(files[i]);
        } else
            this.select(files[0]);
    }

    selectDown() {
        const files = this.#files.filter(f => !f.isHidden());
        if (files.length === 0)
            return;

        if (this.lastSelected) {
            let i = files.indexOf(this.lastSelected);
            const wrap = this.#el.find('div.files-wrap');
            const W = wrap.outerWidth();
            const w = files[0].el.outerWidth(true);
            const cols = Math.floor(W / w);
            const l = files.length;
            if (l <= cols)
                return;

            const rows = Math.ceil(l / cols);
            let row = Math.floor(i / cols);
            const col = i % cols;
            row++;
            if (row === rows)
                row = 0;
            i = row * cols + col;

            this.select(files[i > files.length - 1 ? files.length - 1 : i]);
        } else
            this.select(files[0]);
    }

    deselectAll() {
        this.lastSelected = null;
        this.#files.forEach(f => f.deselect());
    }

    reset() {
        this.deselectAll();
        this.#files.forEach(f => { f.destroy(); });
        this.#files = [];
        this.#el.find('div.files-wrap').html('');
    }
}

export default Container;

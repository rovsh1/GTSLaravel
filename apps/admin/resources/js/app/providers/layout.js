function bootApp() {
    /*const layout = app('layout');

    app().instance('language', layout.getMeta('meta[http-equiv="Content-language"]'));
    app().instance('home_url', layout.getMetaName('home_url'));

    const data = JSON.parse(layout.getMetaName('application-data'));
    if (data) {
        app().instance('appData', data);
        for (let i in data) {
            app().instance(i, data[i]);
        }
        app().bind('userId', () => app('user') ? app('user').id : null);
    }*/
}

function bootSitemap() {
    $('#btn-sitemap').click(() => {
        $(document.body).toggleClass('sitemap-expanded');
    });

    $('#sitemap-categories a').click(function (e) {
        e.preventDefault();

        const item = $(this).parent();
        const category = item.data('category');

        $('#sitemap-categories .current').removeClass('current');
        item.addClass('current');

        const menus = $('#sitemap-categories-menus > div');
        menus.each((i, m) => {
            if ($(m).data('category') === category) $(m).show(); else $(m).hide();
        });
    });
}

function bootGrid() {
    const btn = $('#btn-grid-filters');
    const popup = $('#grid-filters-popup');
    const close = (e) => {
        if (!popup.is(e.target) && popup.find(e.target).length === 0) {
            popup.hide();
            $(document).unbind('click', close);
        }
    };

    btn.click(e => {
        e.preventDefault();
        if (!popup.is(':hidden')) return;

        popup.fadeIn(200);
        $(document).click(close);
        e.stopPropagation();
    });
}

export default function () {
    bootApp();
    bootSitemap();
    bootGrid();

    $('#btn-sidebar-toggle').click(e => {
        $('#sidebar').toggleClass('submenu-collapsed');

        $(this).find('i');
    });
}

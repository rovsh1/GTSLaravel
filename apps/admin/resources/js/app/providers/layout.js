import Layout from "../layout/layout";

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
            if ($(m).data('category') === category)
                $(m).show();
            else
                $(m).hide();
        });
    });
}

export default class LayoutProvider {
    register() {
        app().instance('layout', new Layout());
    }

    boot() {
        bootApp();
        bootSitemap();
    }
}

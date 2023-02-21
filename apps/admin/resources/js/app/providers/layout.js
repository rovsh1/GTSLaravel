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

export default class LayoutProvider {
    register() {
        app().instance('layout', new Layout());
    }

    boot() {
        bootApp();
    }
}

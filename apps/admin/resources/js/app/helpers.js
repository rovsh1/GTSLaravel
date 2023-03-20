function get_meta_content(name, parse) {
    const meta = document.head.querySelector(`meta[name="${name}"]`);
    if (!meta) {
        return null;
    }
    return parse ? JSON.parse(meta.content) : meta.content;
}

function getUrlParameter(name, url) {
    if (!url) {
        url = window.location.href;
    }

    name = name.replace(/[\[\]]/g, '\\$&');
    let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
    let results = regex.exec(url);

    if (!results) {
        return null;
    }

    if (!results[2]) {
        return '';
    }

    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

Object.assign(window, {
    get_meta_content: get_meta_content,
    get_url_parameter: getUrlParameter,
});

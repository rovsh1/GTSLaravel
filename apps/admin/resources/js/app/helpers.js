function get_meta_content(name, parse) {
    const meta = document.head.querySelector('meta[name="' + name + '"]');
    if (!meta) return null;
    return true === parse ? JSON.parse(meta.content) : meta.content;
}

Object.assign(window, {
    get_meta_content: get_meta_content
});

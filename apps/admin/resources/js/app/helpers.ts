function getMetaContent(name: string, parse = false): string | any {
    const meta: HTMLMetaElement | null = document.head.querySelector(`meta[name="${name}"]`)
    if (!meta) {
        return null
    }
    return parse ? JSON.parse(meta?.content) : meta?.content
}

function getUrlParameter(name: string, url: string): string | null {
    if (!url) {
        // eslint-disable-next-line no-param-reassign
        url = window.location.href
    }

    // eslint-disable-next-line no-param-reassign
    name = name.replace(/[[\]]/g, '\\$&')
    const regex = new RegExp(`[?&]${name}(=([^&#]*)|&|#|$)`)
    const results = regex.exec(url)

    if (!results) {
        return null
    }

    if (!results[2]) {
        return ''
    }

    return decodeURIComponent(results[2].replace(/\+/g, ' '))
}

Object.assign(window, {
    get_meta_content: getMetaContent,
    get_url_parameter: getUrlParameter,
});

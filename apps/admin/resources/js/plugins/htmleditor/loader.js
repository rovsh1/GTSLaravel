let loadedFlag = false;

export default async function loadEditor() {
    if (window.tinymce)
        return window.tinymce;
    else if (!loadedFlag) {
        loadedFlag = true;
        return new Promise((resolve) => {
            const head = document.getElementsByTagName('head')[0];
            const script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = '/js/tinymce.js';
            script.onload = function () {
                resolve(window.tinymce);
            };
            head.appendChild(script);
        });
    } else
        return new Promise((resolve) => {
            const timer = function () {
                setTimeout(() => {
                    if (window.tinymce)
                        resolve(window.tinymce);
                    else
                        timer();
                }, 500);
            };
        });
}

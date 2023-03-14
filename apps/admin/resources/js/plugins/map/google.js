let initFlag = false;
let readyFlag = false;
let readyHandlers = [];

window.initMap = () => {
    readyFlag = true;
    readyHandlers.forEach(fn => {
        setTimeout(fn);
    });
    readyHandlers = undefined;
};

const getApiKey = () => {
    return get_meta_content('google-maps-key')
}

export default {
    ready: (fn) => {
        if (readyFlag) {
            fn();
            return;
        }

        if (!initFlag) {
            initFlag = true;
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${getApiKey()}&callback=initMap`;
            script.async = true;
            document.head.appendChild(script)
        }

        readyHandlers.push(fn);
    }
};

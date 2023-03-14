let readyFlag = false;
let readyHandlers = [];

window.initMap = () => {
    readyFlag = true;
    readyHandlers.forEach(fn => {
        fn();
    });
    readyHandlers = undefined;
};

export default {
    init: (key) => {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${key}&callback=initMap`;
        script.async = true;
    },

    ready: (fn) => {
        if (readyFlag)
            fn();
        else
            readyHandlers.push(fn);
    }
};

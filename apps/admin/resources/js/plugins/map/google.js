export const initGoogleMapsScript = () => {
    const googleMapsApiKey = get_meta_content('google-maps-key')

    if (googleMapsApiKey) {
        var script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${googleMapsApiKey}&callback=initMap`;
        script.async = true;

        window.initMap = function () {

        }

        document.head.appendChild(script)
    }
}

$.fn.coordinates = function (options) {
    var $addressInput = $(this);
    var $btn = $('<div class="btn-map"><span class="material-symbols-outlined">map</span></div>');
    var $map = $('#map');
    var $coordinatesInput = $('#form_data_coordinates');
    var maps;
    var geo;
    var marker;

    const getCoordinatesText = (latitude, longitude) => {
        return `${latitude}, ${longitude}`
    }

    const parseCoordinatesText = (text) => {
        const [lat, lng] = text.split(', ')
        return [parseFloat(lat), parseFloat(lng)]
    }

    const setMarker = (lat, lng) => {
        let latLng = {lat: lat, lng: lng};
        if (!marker) {
            marker = new google.maps.Marker({
                map: maps,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: latLng,
            });
        } else {
            marker.setPosition(latLng);
        }
        marker.addListener('dragend', function () {
            let position = marker.getPosition();
            $coordinatesInput.val(getCoordinatesText(position.lat(), position.lng()))
        });
    };

    const setCoordinates = (address = undefined) => {
        if ($coordinatesInput.val().length > 0) {
            const [lat, lng] = parseCoordinatesText($coordinatesInput.val())
            let myLatLng = {lat: lat, lng: lng};
            maps.setCenter(myLatLng);
            setMarker(lat, lng);
            return
        }
        geo = new google.maps.Geocoder();
        geo.geocode({'address': address}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var location = results[0].geometry.location;
                maps.setCenter(location);
                var lat = location.lat();
                var lng = location.lng();
                setMarker(lat, lng);
                $coordinatesInput.val(getCoordinatesText(lat, lng))
                return
            }
            console.log('Not valid address');
        });
    }

    window.initMap = function () {
        if ($map.length === 0) {
            $addressInput.parent().parent().after('<div id="map"></div>')
            $map = $('#map');
        }

        var opt = {
            center: {
                lat: 41.3021517,
                lng: 60.0849903
            },
            zoom: 15
        }
        maps = new google.maps.Map(document.getElementById('map'), opt);
    };

    $btn.click(function (e) {
        e.preventDefault()
        if ($map.css('display') === 'block')
            $map.hide();
        else {
            setCoordinates(address);
            $map.show();
        }
    });

    $addressInput.after($btn);

    const focusOutAddressHandler = () => {
        let address = $addressInput.val();
        if (!address) {
            $btn.hide();
            $map.hide();
        } else {
            $btn.show();
            setCoordinates(address);
        }
    }

    $addressInput.focusout(focusOutAddressHandler);
    $addressInput.change(() => {
        address = $addressInput.val();
        $coordinatesInput.val('')
    });

    if (!address) {
        $btn.hide();
    }

    return $addressInput;
};

import mapProvider from '~resources/js/support/service/google'

const defaultCenterPosition = {
  lat: 41.3021517,
  lng: 60.0849903,
}

function pointToString(point) {
  return [point.lat, point.lng].join(', ')
}

function pointFromString(text) {
  if (text.indexOf(', ') === -1) {
    return null
  }

  const p = text.split(', ')
  const lat = parseFloat(p[0])
  const lng = parseFloat(p[1])
  if (isNaN(lat) || isNaN(lng)) {
    return null
  }

  return {
    lat,
    lng,
  }
}

const mapServices = {
  geocode(map, address, resolve) {
    const geo = new google.maps.Geocoder()
    geo.geocode({ address }, (results, status) => {
      if (status === google.maps.GeocoderStatus.OK) {
        const { location } = results[0].geometry
        map.setCenter(location)
        resolve({
          lat: location.lat(),
          lng: location.lng(),
        })
      } else {
        console.log('Not valid address')
      }
    })
  },
}

$.fn.coordinatesInput = function (options) {
  const preparedOptions = {
    center: defaultCenterPosition,
    map: '#map',
    addressInput: null,
    ...options,
  }

  const $coordinatesInput = $(this)
  const $addressInput = preparedOptions.addressInput ? $(preparedOptions.addressInput) : null
  const $map = $(preparedOptions.map)

  let map
  let marker

  const setMarkerPosition = (point) => {
    marker.setPosition(point)
  }

  const getInputCoordinates = function () {
    return pointFromString($coordinatesInput.val())
  }

  const updateInputCoordinates = function (point) {
    $coordinatesInput.val(pointToString(point))
  }

  const updateCoordinates = (point) => {
    updateInputCoordinates(point)
    setMarkerPosition(point)
  }

  $coordinatesInput.change(() => {
    const point = getInputCoordinates()
    if (point) {
      setMarkerPosition(point)
    }
  })

  if ($addressInput) {
    let addressTimeout

    $addressInput.bind('input', () => {
      if (addressTimeout) {
        clearTimeout(addressTimeout)
        addressTimeout = null
      }

      addressTimeout = setTimeout(() => {
        addressTimeout = null
        const address = $addressInput.val()
        if (address !== '') {
          mapServices.geocode(map, address, updateCoordinates)
        }
      }, 500)
      // $coordinatesInput.val('')
    })
  }

  mapProvider.ready(() => {
    const point = getInputCoordinates() || preparedOptions.center

    map = new google.maps.Map($map[0], {
      center: point,
      zoom: 15,
    })

    marker = new google.maps.Marker({
      map,
      draggable: true,
      animation: google.maps.Animation.DROP,
      position: point,
    })

    google.maps.event.addListener(marker, 'dragend', () => {
      const position = marker.getPosition()
      updateInputCoordinates({
        lat: position.lat(),
        lng: position.lng(),
      })
    })
  })

  return $coordinatesInput
}
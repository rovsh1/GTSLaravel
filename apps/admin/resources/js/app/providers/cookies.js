import timezone from '../support/timezone'

export default function () {
  Object.assign($.cookie.defaults, {
    path: '/',
    domain: location.host.substr(location.host.indexOf('.')),
    // secure: true
  })
  $.cookie('flag_ready', 1)

  timezone.domain = $.cookie.defaults.domain
  timezone.setTimezone()
}

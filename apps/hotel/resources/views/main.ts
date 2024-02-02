import bootCookies from '~resources/js/providers/cookies'
import bootForms from '~resources/js/providers/forms'
import bootGrid from '~resources/js/providers/grids'

import '~resources/js/supports/bootstrap'
import '~resources/js/vendor/jquery.cookie'
import '~widgets/date-picker/date-picker'
import '~widgets/dialog/helpers'
import '~resources/js/supports/select2'
import '~resources/js/plugins/buttons/delete-button'
import '~resources/js/supports/functions'

$(() => {
  $.ajaxSetup({
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
  })

  bootCookies()
  bootForms()
  bootGrid()

  document.body.classList.add('loaded')
})

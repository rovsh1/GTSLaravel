import bootCookies from '~resources/js/app/providers/cookies'
import bootForms from '~resources/js/app/providers/forms'
import bootGrid from '~resources/js/app/providers/grids'

import '~resources/js/supports/bootstrap'
import '~resources/js/vendor/jquery.cookie'
import '~widgets/date-picker/date-picker'
import '~resources/js/libs/dialog/helpers'
import '~resources/js/plugins/select2'
import '~resources/js/plugins/buttons/delete-button'
import '~resources/js/app/support/functions'

$(() => {
  $.ajaxSetup({
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
  })

  bootCookies()
  bootForms()
  bootGrid()

  document.body.classList.add('loaded')
})

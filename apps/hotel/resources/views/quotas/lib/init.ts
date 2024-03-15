import bootCookies from '~resources/js/providers/cookies'
import bootForms from '~resources/js/providers/forms'
import bootGrid from '~resources/js/providers/grids'

import '~resources/js/support/bootstrap'
import '~resources/js/vendor/jquery.cookie'
import 'gts-common/widgets/date-picker'
import 'gts-common/widgets/dialog'
import '~resources/js/plugins/delete-button'
import '~resources/js/support/functions'

$(() => {
  $.ajaxSetup({
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
  })

  bootCookies()
  bootForms()
  bootGrid()

  document.body.classList.add('loaded')
})

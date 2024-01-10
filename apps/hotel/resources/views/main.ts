import bootCookies from '~resources/js/app/providers/cookies'
import bootForms from '~resources/js/app/providers/forms'
import bootGrid from '~resources/js/app/providers/grids'
import bootSitemap from '~resources/js/app/providers/sitemap'

import '~resources/js/bootstrap'
import '~resources/js/vendor/jquery.cookie'
import '~lib/date-picker/date-picker'
import '~resources/js/libs/dialog/helpers'
import '~resources/js/plugins/select2'
import '~resources/js/app/support/functions'

$(() => {
  $.ajaxSetup({
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
  })

  bootSitemap()
  bootCookies()
  bootForms()
  bootGrid()

  document.body.classList.add('loaded')
})

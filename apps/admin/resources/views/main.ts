import bootCookies from '~resources/js/app/providers/cookies'
import bootFilesDownloader from '~resources/js/app/providers/files'
import bootForms from '~resources/js/app/providers/forms'
import bootGrid from '~resources/js/app/providers/grids'
import bootSitemap from '~resources/js/app/providers/sitemap'

import '~resources/js/support/bootstrap'
import '~resources/js/vendor/jquery.cookie'
import '~widgets/date-picker/date-picker'
import '~widgets/dialog/helpers'
import '~resources/js/plugins/multiselect'
import '~resources/js/plugins/child-combo'
import '~resources/js/support/select2'
import '~resources/js/app/support/functions'
import '~resources/js/app/helpers'
import '~resources/js/app/plugins/buttons/delete-button'
import '~resources/js/app/plugins/controls/city'

$(() => {
  $.ajaxSetup({
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
  })

  bootSitemap()
  bootCookies()
  bootForms()
  bootGrid()
  bootFilesDownloader()

  document.body.classList.add('loaded')
})

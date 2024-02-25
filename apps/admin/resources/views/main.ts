import bootActionsmenu from '~resources/js/providers/actions-menu'
import bootCookies from '~resources/js/providers/cookies'
import bootFilesDownloader from '~resources/js/providers/files'
import bootForms from '~resources/js/providers/forms'
import bootGrid from '~resources/js/providers/grids'
import bootSitemap from '~resources/js/providers/sitemap'

import '~resources/js/support/bootstrap'
import '~resources/js/vendor/jquery.cookie'
import 'gts-common/widgets/date-picker/date-picker'
import 'gts-common/widgets/dialog/helpers'
import '~resources/js/plugins/child-combo'
import '~resources/js/support/select2'
import '~resources/js/support/functions'
import '~resources/js/support/helpers'
import '~resources/js/plugins/delete-button'
import '~resources/js/plugins/city'

$(() => {
  $.ajaxSetup({
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
  })

  bootSitemap()
  bootCookies()
  bootForms()
  bootGrid()
  bootFilesDownloader()
  bootActionsmenu()

  document.body.classList.add('loaded')
})

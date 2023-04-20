import '~resources/js/bootstrap'

import '~resources/js/vendor/jquery.cookie'
import '~resources/js/vendor/moment'
import '~resources/js/vendor/daterangepicker'

import '~resources/js/libs/dialog/helpers'

import '~resources/js/plugins/multiselect'
import '~resources/js/plugins/child-combo'

import '~resources/js/app/support/functions'
import '~resources/js/app/helpers'
import '~resources/js/app/plugins/buttons/delete-button'
import '~resources/js/app/plugins/controls/city'

import bootCookies from '~resources/js/app/providers/cookies'
import bootForms from '~resources/js/app/providers/forms'
import bootGrid from '~resources/js/app/providers/grids'
import bootSitemap from '~resources/js/app/providers/sitemap'

$(document).ready(() => {
  $.ajaxSetup({
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
  })

  bootSitemap()
  bootCookies()
  bootForms()
  bootGrid()

  document.body.classList.add('loaded')
})

import initServicesModal from '~resources/views/show/_modals/services-modal'
import initUsabilitiesModal from '~resources/views/show/_modals/usabilities-modal'

import '~resources/views/main'
import '~resources/js/app/components/card-contacts'

$(document).ready((): void => {
  $('#btn-free-services')
    .click(function (e: any) {
      e.preventDefault()
      window.WindowDialog({
        url: $(this)
          .data('url'),
        title: 'Изменить услуги отеля',
        buttons: ['submit', 'cancel'],
        update: (): void => {
          initServicesModal()
        },
      })
    })

  $('#btn-paid-services')
    .click(function (e: any) {
      e.preventDefault()
      window.WindowDialog({
        url: $(this)
          .data('url'),
        title: 'Изменить услуги отеля',
        buttons: ['submit', 'cancel'],
        update: (): void => {
          initServicesModal()
        },
      })
    })

  $('#btn-usabilities')
    .click(function (e: any): void {
      e.preventDefault()
      window.WindowDialog({
        url: $(this)
          .data('url'),
        title: 'Изменить удобства отеля',
        buttons: ['submit', 'cancel'],
        update: (): void => {
          initUsabilitiesModal()
        },
      })
    })
})

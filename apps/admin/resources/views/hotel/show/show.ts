import { editableTable } from '~resources/js/app/support/editable-table'
import initServicesModal from '~resources/views/hotel/_modals/services-modal'
import initUsabilitiesModal from '~resources/views/hotel/_modals/usabilities-modal'

import '~resources/views/main'
import '~resources/js/app/components/card-contacts'

$(document)
  .ready((): void => {
    $('#card-contacts')
      .cardContacts()

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

    $('#btn-hotel-landmarks')
      .click(function (e: any) {
        e.preventDefault()
        window.WindowDialog({
          url: $(this)
            .data('url'),
          title: 'Добавить объект',
          buttons: ['submit', 'cancel'],
        })
      })

    editableTable({
      $table: $('#hotel-landmark-grid'),
      route: window.get_meta_content('hotel-landmark-base-route'),
      canEdit: false,
    })
  })
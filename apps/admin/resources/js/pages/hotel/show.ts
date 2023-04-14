import '../main'
import '../../app/components/card-contacts'
import initServicesModal from './modals/services-modal'
import initUsabilitiesModal from './modals/usabilities-modal'

$(document).ready((): void => {
  $('#card-contacts').cardContacts()

  $('#btn-free-services').click(function (e: any) {
    e.preventDefault()
    window.WindowDialog({
      url: $(this).data('url'),
      title: 'Изменить услуги отеля',
      buttons: ['submit', 'cancel'],
      update: (): void => {
        initServicesModal()
      },
    })
  })

  $('#btn-paid-services').click(function (e: any) {
    e.preventDefault()
    window.WindowDialog({
      url: $(this).data('url'),
      title: 'Изменить услуги отеля',
      buttons: ['submit', 'cancel'],
      update: (): void => {
        initServicesModal()
      },
    })
  })

  $('#btn-usabilities').click(function (e: any): void {
    e.preventDefault()
    window.WindowDialog({
      url: $(this).data('url'),
      title: 'Изменить удобства отеля',
      buttons: ['submit', 'cancel'],
      update: (): void => {
        initUsabilitiesModal()
      },
    })
  })
})

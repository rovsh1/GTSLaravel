import axios from '~resources/js/app/api'

import { BookingRequest } from '~api/booking/request'
import { BookingAvailableActionsResponse } from '~api/booking/status'
import { BookingVoucher } from '~api/booking/voucher'

import { showConfirmDialog } from '~lib/confirm-dialog'

import '~resources/views/main'

$(() => {
  // $('#form_data_city_id').elementCity({ countrySelector: '#form_data_country_id' })

  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
  })

  $('#form_data_hotel_room_id').childCombo({
    urlGetter: (hotelId: number) => `/hotels/${hotelId}/rooms/list`,
    disabledText: 'Выберите отель',
    parent: $('#form_data_hotel_id'),
    dataIndex: 'hotel_id',
  })

  const selectedBookings: string[] = []

  const $deleteBookingsButton = $('<a />', {
    href: '#',
    html: '<i class="icon">delete</i>Удалить брони',
    class: 'btn btn-delete text-danger border-0 disabled',
  }).click(async (event) => {
    event.preventDefault()

    const { result: isConfirmed, toggleLoading } = await showConfirmDialog('Удалить запись?', 'btn-danger')
    if (isConfirmed) {
      toggleLoading()
      await axios.delete('/hotel-booking/bulk', { data: { ids: selectedBookings } })
      location.reload()
    }
  })

  $('.content-header a.btn-add').after($deleteBookingsButton)

  $('.js-select-booking').change((event: any): void => {
    const $checkbox = $(event.target)
    const bookingId = $checkbox.data('booking-id')
    if ($checkbox.is(':checked')) {
      selectedBookings.push(bookingId)
      $deleteBookingsButton.toggleClass('disabled', false)
      return
    }

    const index = selectedBookings.indexOf(bookingId)
    if (index !== -1) {
      selectedBookings.splice(index, 1)
      if (selectedBookings.length === 0) {
        $deleteBookingsButton.toggleClass('disabled', true)
      }
    }
  })

  $('td.column-actions').each(function () {
    const bookingId = $(this).parent().data('id')

    const getNewIconButton = (icon: string) => $('<a />', { href: '#', html: `<i class="icon">${icon}</i>` })

    const $sendButton = getNewIconButton('mail')
      .on('click', async (e: any) => {
        e.preventDefault()
        const { data: availableActions } = await axios.get<BookingAvailableActionsResponse>(`/hotel-booking/${bookingId}/actions/available`)
        console.log(bookingId, availableActions)

        let requestText
        if (availableActions.canSendBookingRequest) {
          requestText = 'Запрос на бронирование еще не отправлен'
        }
        if (availableActions.canSendChangeRequest) {
          requestText = 'Ожидание изменений и отправки запроса'
        }
        if (availableActions.canSendCancellationRequest) {
          requestText = 'Бронирование подтверждено, до выставления счета доступен запрос на отмену'
        }
        if (availableActions.isRequestable && requestText) {
          // @todo выводим в popover тект + кнопку "отправить" handler: axios.post(`/${bookingId}/request`)
        }
        if (availableActions.canSendVoucher) {
          const voucherText = 'При необходимости клиенту можно отправить ваучер'
          console.log(voucherText)
          // @todo также вывести в поповер текст + кнопку "отправить" handler: axios.post(`/${bookingId}/voucher`)
        }
      })

    const $downloadButton = getNewIconButton('download')
      .on('click', async (e: any) => {
        e.preventDefault()
        const [{ data: requests }, { data: vouchers }] = await Promise.all([
          axios.get<BookingRequest[]>(`/hotel-booking/${bookingId}/request/list`),
          axios.get<BookingVoucher[]>(`/hotel-booking/${bookingId}/voucher/list`),
        ])
        console.log(requests)
        console.log(vouchers)
        // @todo сгруппировать запросы по параметру type, вывести все type по одной штуке(последний по дате) и вывести все ваучеры в поповер + кнопка "Скачать" у каждого
        // @todo скачивание файлов реализовано тут apps/admin/resources/api/booking/document.ts:39
      })

    const $buttonsWrapper = $('<div />', { class: 'd-flex flex-row gap-2' }).append($sendButton).append($downloadButton)

    $(this).append($buttonsWrapper)
  })
})

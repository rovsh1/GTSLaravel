import axios from '~resources/js/app/api'

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
})
